<?php

declare(strict_types=1);

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\EbdClass;
use App\Models\Student;
use App\Services\AuditService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Student::query()->with('ebdClass')->orderBy('name');

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
        $classes = EbdClass::active()->orderBy('name')->get();

        return view('secretaria.students.index', [
            'students' => $students,
            'turmas' => $classes,
            'classes' => $classes,
        ]);
    }

    public function create(): View
    {
        $classes = EbdClass::active()->orderBy('name')->get();

        return view('secretaria.students.create', [
            'turmas' => $classes,
            'classes' => $classes,
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        $student = Student::create($validated);

        AuditService::log(
            'STUDENT_CREATED',
            Student::class,
            $student->id,
            null,
            ['name' => $student->name, 'class_id' => $student->class_id]
        );

        return redirect()->route('alunos.index')->with('success', "Aluno {$student->name} cadastrado com sucesso!");
    }

    public function edit(Student $aluno): View
    {
        $classes = EbdClass::active()->orderBy('name')->get();

        return view('secretaria.students.edit', [
            'student' => $aluno,
            'turmas' => $classes,
            'classes' => $classes,
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $aluno): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

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
        $oldStatus = $aluno->is_active;
        $aluno->is_active = !$oldStatus;
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
