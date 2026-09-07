<?php

declare(strict_types=1);

namespace App\Http\Controllers\Secretaria;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;
use App\Models\EbdClass;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ClassController extends Controller
{
    public function index(Request $request): View
    {
        $query = EbdClass::query()->withCount(['students', 'teachers'])->orderBy('name');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $classes = $query->paginate(15)->withQueryString();

        return view('secretaria.classes.index', [
            'turmas' => $classes,
            'classes' => $classes,
        ]);
    }

    public function create(): View
    {
        $teachers = User::where('role', UserRole::PROFESSOR)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('secretaria.classes.create', [
            'teachers' => $teachers,
        ]);
    }

    public function store(StoreClassRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);
        $teacherIds = $validated['teacher_ids'] ?? [];

        $class = DB::transaction(function () use ($validated, $teacherIds) {
            $class = EbdClass::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            if (!empty($teacherIds)) {
                $class->teachers()->sync($teacherIds);
            }

            return $class;
        });

        AuditService::log(
            'CLASS_CREATED',
            EbdClass::class,
            $class->id,
            null,
            ['name' => $class->name, 'teachers_count' => count($teacherIds)]
        );

        return redirect()->route('classes.index')->with('success', "Classe {$class->name} criada com sucesso!");
    }

    public function edit(EbdClass $class): View
    {
        $class->load('teachers');
        $teachers = User::where('role', UserRole::PROFESSOR)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('secretaria.classes.edit', [
            'class' => $class,
            'teachers' => $teachers,
            'selectedTeacherIds' => $class->teachers->pluck('id')->toArray(),
        ]);
    }

    public function update(UpdateClassRequest $request, EbdClass $class): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');
        $teacherIds = $validated['teacher_ids'] ?? [];

        $before = [
            'name' => $class->name,
            'description' => $class->description,
            'is_active' => $class->is_active,
            'teacher_ids' => $class->teachers()->pluck('users.id')->toArray(),
        ];

        DB::transaction(function () use ($class, $validated, $teacherIds) {
            $class->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'],
            ]);

            $class->teachers()->sync($teacherIds);
        });

        AuditService::log(
            'CLASS_UPDATED',
            EbdClass::class,
            $class->id,
            $before,
            [
                'name' => $class->name,
                'description' => $class->description,
                'is_active' => $class->is_active,
                'teacher_ids' => $teacherIds,
            ]
        );

        return redirect()->route('classes.index')->with('success', "Classe {$class->name} atualizada com sucesso!");
    }

    public function toggleActive(EbdClass $class): RedirectResponse
    {
        $oldStatus = $class->is_active;
        $class->is_active = !$oldStatus;
        $class->save();

        AuditService::log(
            'CLASS_STATUS_TOGGLED',
            EbdClass::class,
            $class->id,
            ['is_active' => $oldStatus],
            ['is_active' => $class->is_active]
        );

        $statusMsg = $class->is_active ? 'ativada' : 'desativada';
        return redirect()->back()->with('success', "Classe {$class->name} {$statusMsg} com sucesso!");
    }
}
