<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\TakeAttendance;
use App\Models\Congregation;
use App\Models\EbdClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Congregation $congregation;
    protected User $professor;
    protected User $otherProfessor;
    protected EbdClass $classA;
    protected EbdClass $classB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->congregation = Congregation::firstOrCreate(
            ['slug' => 'templo-sede-test'],
            ['name' => 'Templo Sede', 'is_headquarters' => true, 'is_active' => true]
        );

        $this->professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'congregation_id' => $this->congregation->id,
            'is_active' => true,
        ]);

        $this->otherProfessor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'congregation_id' => $this->congregation->id,
            'is_active' => true,
        ]);

        $this->classA = EbdClass::create([
            'name' => 'Classe dos Adultos',
            'congregation_id' => $this->congregation->id,
            'is_active' => true,
        ]);
        $this->classA->teachers()->attach($this->professor->id);

        $this->classB = EbdClass::create([
            'name' => 'Classe dos Jovens',
            'congregation_id' => $this->congregation->id,
            'is_active' => true,
        ]);
        $this->classB->teachers()->attach($this->otherProfessor->id);
    }

    public function test_professor_can_only_see_students_of_their_classes(): void
    {
        $studentA = Student::create([
            'name' => 'Aluno Classe A',
            'class_id' => $this->classA->id,
            'congregation_id' => $this->congregation->id,
            'is_active' => true,
        ]);

        $studentB = Student::create([
            'name' => 'Aluno Classe B',
            'class_id' => $this->classB->id,
            'congregation_id' => $this->congregation->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->professor)->get(route('alunos.index'));

        $response->assertStatus(200);
        $response->assertSee('Aluno Classe A');
        $response->assertDontSee('Aluno Classe B');
    }

    public function test_professor_can_access_create_screen_and_sees_only_assigned_classes(): void
    {
        $response = $this->actingAs($this->professor)->get(route('alunos.create'));

        $response->assertStatus(200);
        $response->assertSee('Classe dos Adultos');
        $response->assertDontSee('Classe dos Jovens');
    }

    public function test_professor_can_enroll_student_in_their_class(): void
    {
        $response = $this->actingAs($this->professor)->post(route('alunos.store'), [
            'name' => 'Novo Aluno Matriculado',
            'class_id' => $this->classA->id,
            'phone' => '82988887777',
            'birth_date' => '2000-01-15',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('alunos.index'));
        $this->assertDatabaseHas('students', [
            'name' => 'Novo Aluno Matriculado',
            'class_id' => $this->classA->id,
            'congregation_id' => $this->congregation->id,
        ]);
    }

    public function test_professor_cannot_enroll_student_in_another_class(): void
    {
        $response = $this->actingAs($this->professor)->post(route('alunos.store'), [
            'name' => 'Aluno Invasor',
            'class_id' => $this->classB->id,
            'phone' => '82988887777',
            'is_active' => true,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('students', [
            'name' => 'Aluno Invasor',
        ]);
    }

    public function test_professor_can_edit_and_update_student_in_their_class(): void
    {
        $student = Student::create([
            'name' => 'Aluno Original',
            'class_id' => $this->classA->id,
            'congregation_id' => $this->congregation->id,
            'is_active' => true,
        ]);

        $this->actingAs($this->professor)
            ->get(route('alunos.edit', $student))
            ->assertStatus(200);

        $response = $this->actingAs($this->professor)
            ->put(route('alunos.update', $student), [
                'name' => 'Aluno Nome Atualizado',
                'class_id' => $this->classA->id,
                'phone' => '82999990000',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('alunos.index'));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Aluno Nome Atualizado',
            'phone' => '82999990000',
        ]);
    }

    public function test_professor_cannot_edit_or_update_student_of_another_class(): void
    {
        $studentB = Student::create([
            'name' => 'Aluno Outra Turma',
            'class_id' => $this->classB->id,
            'congregation_id' => $this->congregation->id,
            'is_active' => true,
        ]);

        $this->actingAs($this->professor)
            ->get(route('alunos.edit', $studentB))
            ->assertStatus(403);

        $this->actingAs($this->professor)
            ->put(route('alunos.update', $studentB), [
                'name' => 'Tentativa De Hack',
                'class_id' => $this->classB->id,
            ])
            ->assertStatus(403);
    }

    public function test_professor_can_toggle_active_status_of_their_student(): void
    {
        $student = Student::create([
            'name' => 'Aluno Ativo',
            'class_id' => $this->classA->id,
            'congregation_id' => $this->congregation->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->professor)
            ->patch(route('alunos.toggle', $student));

        $response->assertStatus(302);
        $this->assertFalse((bool) $student->fresh()->is_active);
    }

    public function test_professor_cannot_permanently_delete_students(): void
    {
        $student = Student::create([
            'name' => 'Aluno Para Nao Deletar',
            'class_id' => $this->classA->id,
            'congregation_id' => $this->congregation->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->professor)
            ->delete(route('alunos.destroy', $student));

        $response->assertStatus(403);
        $this->assertDatabaseHas('students', ['id' => $student->id]);
    }

    public function test_professor_can_quick_enroll_student_in_take_attendance_component(): void
    {
        $this->actingAs($this->professor);

        Livewire::test(TakeAttendance::class, ['classId' => $this->classA->id])
            ->assertSet('showQuickEnrollModal', false)
            ->call('openQuickEnroll')
            ->assertSet('showQuickEnrollModal', true)
            ->set('newStudentName', 'Visitante Que Matriculou')
            ->set('newStudentPhone', '82977776666')
            ->set('newStudentBirthDate', '1995-06-20')
            ->call('quickEnrollStudent')
            ->assertSet('showQuickEnrollModal', false)
            ->assertSee('Visitante Que Matriculou');

        $newStudent = Student::where('name', 'Visitante Que Matriculou')->first();
        $this->assertNotNull($newStudent);
        $this->assertEquals($this->classA->id, $newStudent->class_id);
        $this->assertEquals($this->congregation->id, $newStudent->congregation_id);
        $this->assertTrue((bool) $newStudent->is_active);
    }
}
