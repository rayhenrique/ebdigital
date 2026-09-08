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

    public function create(): View|RedirectResponse
    {
        $congregationId = $this->tenantService->getCongregationId() ?? Auth::user()?->congregation_id;

        if (! $congregationId) {
            return redirect()->route('professores.index')
                ->with('warning', 'Selecione uma congregação para cadastrar um professor.');
        }

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

        $congregationId = $this->tenantService->getCongregationId() ?? $user->congregation_id;

        if (! $congregationId) {
            return redirect()->route('professores.index')
                ->with('warning', 'Selecione uma congregação para cadastrar um professor.');
        }

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

    public function edit(User $professor): View
    {
        $this->authorizeTeacherAccess($professor);

        $professor->load('teachingClasses');
        $classes = EbdClass::active()->orderBy('name')->get();

        return view('secretaria.teachers.edit', [
            'teacher' => $professor,
            'classes' => $classes,
            'selectedClassIds' => $professor->teachingClasses->pluck('id')->toArray(),
        ]);
    }

    public function update(UpdateTeacherRequest $request, User $professor): RedirectResponse
    {
        $this->authorizeTeacherAccess($professor);

        $validated = $request->validated();
        $classIds = $validated['class_ids'] ?? [];

        $before = [
            'name' => $professor->name,
            'email' => $professor->email,
            'is_active' => $professor->is_active,
        ];

        DB::transaction(function () use ($validated, $professor, $classIds) {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'is_active' => $validated['is_active'] ?? true,
            ];

            if (! empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $professor->update($updateData);
            $professor->teachingClasses()->sync($classIds);
        });

        AuditService::log(
            'TEACHER_UPDATED',
            User::class,
            $professor->id,
            $before,
            ['name' => $professor->name, 'email' => $professor->email, 'is_active' => $professor->is_active]
        );

        return redirect()->route('professores.index')
            ->with('success', "Professor {$professor->name} atualizado com sucesso!");
    }

    public function toggleActive(User $professor): RedirectResponse
    {
        $this->authorizeTeacherAccess($professor);

        $professor->is_active = ! $professor->is_active;
        $professor->save();

        AuditService::log(
            'TEACHER_STATUS_TOGGLED',
            User::class,
            $professor->id,
            null,
            ['is_active' => $professor->is_active]
        );

        $status = $professor->is_active ? 'ativado' : 'desativado';
        return redirect()->back()->with('success', "Professor {$professor->name} {$status} com sucesso!");
    }

    public function resetPassword(Request $request, User $professor): RedirectResponse
    {
        $this->authorizeTeacherAccess($professor);

        $request->validate([
            'password' => ['required', 'string', 'min:6'],
        ]);

        $professor->update([
            'password' => Hash::make($request->input('password')),
        ]);

        AuditService::log(
            'TEACHER_PASSWORD_RESET',
            User::class,
            $professor->id,
            null,
            ['reset_by' => Auth::id()]
        );

        return redirect()->back()->with('success', "Senha de {$professor->name} redefinida com sucesso!");
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
