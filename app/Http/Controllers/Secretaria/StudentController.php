<?php

declare(strict_types=1);

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\EbdClass;
use App\Models\Student;
use App\Services\AuditService;
use App\Services\TenantService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(
        protected TenantService $tenantService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = Student::query()
            ->with(['ebdClass', 'congregation'])
            ->orderBy('name');

        if ($user->isProfessor()) {
            $myClassIds = $user->teachingClasses()->pluck('classes.id');
            $query->whereIn('class_id', $myClassIds);
            $classes = $user->teachingClasses()->where('is_active', true)->orderBy('name')->get();
        } else {
            $classes = EbdClass::active()->orderBy('name')->get();
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->input('class_id'));
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $students = $query->paginate(20)->withQueryString();

        return view('secretaria.students.index', [
            'students' => $students,
            'turmas' => $classes,
            'classes' => $classes,
            'isAllCongregations' => $this->tenantService->isAllCongregations(),
            'isProfessor' => $user->isProfessor(),
        ]);
    }

    public function create(): View|RedirectResponse
    {
        if ($this->tenantService->isAllCongregations()) {
            return redirect()->route('alunos.index')
                ->with('warning', 'Selecione uma congregação no menu para poder cadastrar alunos.');
        }

        $user = Auth::user();

        if ($user->isProfessor()) {
            $classes = $user->teachingClasses()->where('is_active', true)->orderBy('name')->get();
            if ($classes->isEmpty()) {
                return redirect()->route('alunos.index')
                    ->with('warning', 'Você não está vinculado a nenhuma classe ativa para matricular alunos.');
            }
        } else {
            $classes = EbdClass::active()->orderBy('name')->get();
        }

        return view('secretaria.students.create', [
            'turmas' => $classes,
            'classes' => $classes,
            'isProfessor' => $user->isProfessor(),
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        if ($this->tenantService->isAllCongregations()) {
            return redirect()->route('alunos.index')
                ->with('warning', 'Selecione uma congregação no menu para poder cadastrar alunos.');
        }

        $user = Auth::user();
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        $class = EbdClass::findOrFail($validated['class_id']);

        if ($user->isProfessor()) {
            $teachesClass = $user->teachingClasses()->where('classes.id', $class->id)->exists();
            if (! $teachesClass) {
                abort(403, 'Você só possui permissão para matricular alunos em suas próprias turmas.');
            }
        }

        $validated['congregation_id'] = $class->congregation_id;

        $student = Student::create($validated);

        AuditService::log(
            'STUDENT_CREATED',
            Student::class,
            $student->id,
            null,
            ['name' => $student->name, 'class_id' => $student->class_id, 'congregation_id' => $student->congregation_id]
        );

        return redirect()->route('alunos.index')->with('success', "Aluno {$student->name} cadastrado com sucesso!");
    }

    public function edit(Student $aluno): View|RedirectResponse
    {
        if ($this->tenantService->isAllCongregations()) {
            return redirect()->route('alunos.index')
                ->with('warning', 'Selecione uma congregação no menu para poder editar alunos.');
        }

        $user = Auth::user();

        if ($user->isProfessor()) {
            $teachesCurrent = $user->teachingClasses()->where('classes.id', $aluno->class_id)->exists();
            if (! $teachesCurrent) {
                abort(403, 'Você só possui permissão para editar alunos de suas próprias turmas.');
            }
            $classes = $user->teachingClasses()->where('is_active', true)->orderBy('name')->get();
        } else {
            $classes = EbdClass::active()->orderBy('name')->get();
        }

        return view('secretaria.students.edit', [
            'student' => $aluno,
            'turmas' => $classes,
            'classes' => $classes,
            'isProfessor' => $user->isProfessor(),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $aluno): RedirectResponse
    {
        if ($this->tenantService->isAllCongregations()) {
            return redirect()->route('alunos.index')
                ->with('warning', 'Selecione uma congregação no menu para poder editar alunos.');
        }

        $user = Auth::user();

        if ($user->isProfessor()) {
            $teachesCurrent = $user->teachingClasses()->where('classes.id', $aluno->class_id)->exists();
            if (! $teachesCurrent) {
                abort(403, 'Você só possui permissão para editar alunos de suas próprias turmas.');
            }
        }

        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        if (isset($validated['class_id'])) {
            $class = EbdClass::findOrFail($validated['class_id']);
            if ($user->isProfessor()) {
                $teachesNew = $user->teachingClasses()->where('classes.id', $class->id)->exists();
                if (! $teachesNew) {
                    abort(403, 'Você só pode transferir o aluno para outra turma que você leciona.');
                }
            }
            $validated['congregation_id'] = $class->congregation_id;
        }

        $beforeBirthDate = $aluno->birth_date ? Carbon::parse($aluno->birth_date)->format('Y-m-d') : null;

        $before = [
            'name' => $aluno->name,
            'class_id' => $aluno->class_id,
            'phone' => $aluno->phone,
            'birth_date' => $beforeBirthDate,
            'is_active' => $aluno->is_active,
        ];

        $aluno->update($validated);

        $afterBirthDate = $aluno->birth_date ? Carbon::parse($aluno->birth_date)->format('Y-m-d') : null;

        AuditService::log(
            'STUDENT_UPDATED',
            Student::class,
            $aluno->id,
            $before,
            [
                'name' => $aluno->name,
                'class_id' => $aluno->class_id,
                'phone' => $aluno->phone,
                'birth_date' => $afterBirthDate,
                'is_active' => $aluno->is_active,
            ]
        );

        return redirect()->route('alunos.index')->with('success', "Aluno {$aluno->name} atualizado com sucesso!");
    }

    public function toggleActive(Student $aluno): RedirectResponse
    {
        if ($this->tenantService->isAllCongregations()) {
            return redirect()->route('alunos.index')
                ->with('warning', 'Selecione uma congregação no menu para poder alterar o status do aluno.');
        }

        $user = Auth::user();
        if ($user->isProfessor()) {
            $teachesCurrent = $user->teachingClasses()->where('classes.id', $aluno->class_id)->exists();
            if (! $teachesCurrent) {
                abort(403, 'Você só possui permissão para alterar status de alunos de suas próprias turmas.');
            }
        }

        $oldStatus = $aluno->is_active;
        $aluno->is_active = ! $oldStatus;
        $aluno->save();

        AuditService::log(
            'STUDENT_STATUS_TOGGLED',
            Student::class,
            $aluno->id,
            ['is_active' => $oldStatus],
            ['is_active' => $aluno->is_active]
        );

        $statusMsg = $aluno->is_active ? 'ativado' : 'desativado';
        return redirect()->back()->with('success', "Aluno {$aluno->name} {$statusMsg} com sucesso!");
    }

    public function destroy(Student $aluno): RedirectResponse
    {
        if ($this->tenantService->isAllCongregations()) {
            return redirect()->route('alunos.index')
                ->with('warning', 'Selecione uma congregação no menu para poder excluir alunos.');
        }

        $user = Auth::user();
        if ($user->isProfessor()) {
            abort(403, 'Professores não possuem permissão para excluir permanentemente registros de alunos. Desative o aluno para arquivá-lo.');
        }

        if ($aluno->attendances()->exists()) {
            return redirect()->back()->with('error', "O aluno '{$aluno->name}' possui presenças registradas em chamadas e não pode ser excluído para não corromper o histórico. Desative o aluno para arquivá-lo.");
        }

        $studentName = $aluno->name;
        $studentId = $aluno->id;
        $classId = $aluno->class_id;

        $aluno->delete();

        AuditService::log(
            'STUDENT_DELETED',
            Student::class,
            $studentId,
            ['name' => $studentName, 'class_id' => $classId],
            null
        );

        return redirect()->route('alunos.index')->with('success', "Aluno '{$studentName}' excluído com sucesso!");
    }
}
