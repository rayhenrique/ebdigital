<?php

declare(strict_types=1);

namespace App\Http\Controllers\Secretaria;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Secretaria\StoreTeacherRequest;
use App\Http\Requests\Secretaria\UpdateTeacherRequest;
use App\Models\EbdClass;
use App\Models\User;
use App\Services\AuditService;
use App\Services\TenantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function __construct(
        protected TenantService $tenantService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user();
        $congregationId = $this->tenantService->getCongregationId();

        $query = User::where('role', UserRole::PROFESSOR)
            ->with(['teachingClasses', 'congregation'])
            ->orderBy('name');

        if ($congregationId !== null) {
            $query->where('congregation_id', $congregationId);
        } elseif (! $user->isAdmin()) {
            $query->where('congregation_id', $user->congregation_id ?? 1);
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $teachers = $query->paginate(15)->withQueryString();

        return view('secretaria.teachers.index', [
            'teachers' => $teachers,
            'currentCongregation' => $this->tenantService->getCongregation(),
        ]);
    }

    public function create(): View
    {
        $classes = EbdClass::active()->orderBy('name')->get();

        return view('secretaria.teachers.create', [
            'classes' => $classes,
            'currentCongregation' => $this->tenantService->getCongregation(),
        ]);
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $user = Auth::user();

        // Garante a vinculação correta da congregação
        $congregationId = $this->tenantService->getCongregationId() ?? $user->congregation_id ?? 1;

        $classIds = $validated['class_ids'] ?? [];

        $teacher = DB::transaction(function () use ($validated, $congregationId, $classIds) {
            $teacher = User::create([
                'congregation_id' => $congregationId,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => UserRole::PROFESSOR,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            if (! empty($classIds)) {
                $teacher->teachingClasses()->sync($classIds);
            }

            return $teacher;
        });

        AuditService::log(
            'TEACHER_CREATED',
            User::class,
            $teacher->id,
            null,
            ['name' => $teacher->name, 'email' => $teacher->email, 'congregation_id' => $congregationId]
        );

        return redirect()->route('professores.index')
            ->with('success', "Professor {$teacher->name} cadastrado com sucesso!");
    }

    public function edit(User $professore): View
    {
        $this->authorizeTeacherAccess($professore);

        $professore->load('teachingClasses');
        $classes = EbdClass::active()->orderBy('name')->get();

        return view('secretaria.teachers.edit', [
            'teacher' => $professore,
            'classes' => $classes,
            'selectedClassIds' => $professore->teachingClasses->pluck('id')->toArray(),
        ]);
    }

    public function update(UpdateTeacherRequest $request, User $professore): RedirectResponse
    {
        $this->authorizeTeacherAccess($professore);

        $validated = $request->validated();
        $classIds = $validated['class_ids'] ?? [];

        $before = [
            'name' => $professore->name,
            'email' => $professore->email,
            'is_active' => $professore->is_active,
        ];

        DB::transaction(function () use ($validated, $professore, $classIds) {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'is_active' => $validated['is_active'] ?? true,
            ];

            if (! empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $professore->update($updateData);
            $professore->teachingClasses()->sync($classIds);
        });

        AuditService::log(
            'TEACHER_UPDATED',
            User::class,
            $professore->id,
            $before,
            ['name' => $professore->name, 'email' => $professore->email, 'is_active' => $professore->is_active]
        );

        return redirect()->route('professores.index')
            ->with('success', "Professor {$professore->name} atualizado com sucesso!");
    }

    public function toggleActive(User $professore): RedirectResponse
    {
        $this->authorizeTeacherAccess($professore);

        $professore->is_active = ! $professore->is_active;
        $professore->save();

        AuditService::log(
            'TEACHER_STATUS_TOGGLED',
            User::class,
            $professore->id,
            null,
            ['is_active' => $professore->is_active]
        );

        $status = $professore->is_active ? 'ativado' : 'desativado';
        return redirect()->back()->with('success', "Professor {$professore->name} {$status} com sucesso!");
    }

    public function resetPassword(Request $request, User $professore): RedirectResponse
    {
        $this->authorizeTeacherAccess($professore);

        $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ]);

        $professore->update([
            'password' => Hash::make($request->input('password')),
        ]);

        AuditService::log(
            'TEACHER_PASSWORD_RESET',
            User::class,
            $professore->id,
            null,
            ['reset_by' => Auth::id()]
        );

        return redirect()->back()->with('success', "Senha de {$professore->name} redefinida com sucesso!");
    }

    /**
     * Valida se o usuário autenticado tem permissão para gerenciar este professor.
     */
    protected function authorizeTeacherAccess(User $teacher): void
    {
        $user = Auth::user();

        // O usuário alvo precisa ser professor
        if (! $teacher->isProfessor()) {
            abort(403, 'Apenas contas de professores podem ser gerenciadas neste módulo.');
        }

        // Se for secretário, precisa ser estritamente da mesma congregação
        if ($user->isSecretario()) {
            $userCongregation = $user->congregation_id ?? 1;
            $teacherCongregation = $teacher->congregation_id ?? 1;

            if ($userCongregation !== $teacherCongregation) {
                abort(403, 'Você não possui permissão para gerenciar professores de outra congregação.');
            }
        }
    }
}
