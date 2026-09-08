<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\EbdClass;
use App\Models\LessonAttendance;
use App\Models\LessonRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudDestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_empty_class_can_be_deleted_by_admin(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'congregation_id' => 1,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'name' => 'Turma Teste',
            'congregation_id' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['selected_congregation_id' => 1])
            ->delete(route('classes.destroy', $class));

        $response->assertRedirect(route('classes.index'));
        $this->assertDatabaseMissing('classes', ['id' => $class->id]);
    }

    public function test_class_with_students_cannot_be_deleted(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'congregation_id' => 1,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'name' => 'Turma Com Alunos',
            'congregation_id' => 1,
            'is_active' => true,
        ]);

        Student::create([
            'class_id' => $class->id,
            'name' => 'Aluno Teste',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['selected_congregation_id' => 1])
            ->delete(route('classes.destroy', $class));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('classes', ['id' => $class->id]);
    }

    public function test_student_without_attendance_can_be_deleted(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'congregation_id' => 1,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'name' => 'Classe Primários',
            'congregation_id' => 1,
            'is_active' => true,
        ]);

        $student = Student::create([
            'class_id' => $class->id,
            'congregation_id' => 1,
            'name' => 'Aluno Sem Frequência',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['selected_congregation_id' => 1])
            ->delete(route('alunos.destroy', $student));

        $response->assertRedirect(route('alunos.index'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_student_with_attendance_cannot_be_deleted(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'congregation_id' => 1,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'name' => 'Classe Juvenis',
            'congregation_id' => 1,
            'is_active' => true,
        ]);

        $student = Student::create([
            'class_id' => $class->id,
            'congregation_id' => 1,
            'name' => 'Aluno Com Frequência',
            'is_active' => true,
        ]);

        $record = LessonRecord::create([
            'class_id' => $class->id,
            'congregation_id' => 1,
            'registered_by' => $admin->id,
            'lesson_date' => now()->format('Y-m-d'),
        ]);

        LessonAttendance::create([
            'lesson_record_id' => $record->id,
            'student_id' => $student->id,
            'is_present' => true,
        ]);

        $response = $this->actingAs($admin)
            ->withSession(['selected_congregation_id' => 1])
            ->delete(route('alunos.destroy', $student));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('students', ['id' => $student->id]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_clean_data_command_wipes_classes_and_students_but_keeps_users(): void
    {
        $user = User::factory()->create(['role' => UserRole::ADMIN]);

        $class = EbdClass::create(['name' => 'Turma Command', 'is_active' => true]);
        Student::create(['class_id' => $class->id, 'name' => 'Aluno Command', 'is_active' => true]);

        $this->artisan('ebd:clean-data --force')
            ->assertExitCode(0);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('classes', 0);
        $this->assertDatabaseCount('students', 0);
    }
}
