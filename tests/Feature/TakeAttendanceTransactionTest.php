<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\EbdClass;
use App\Models\LessonAttendance;
use App\Models\LessonRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TakeAttendanceTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_saved_with_transaction_and_audit_log(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'name' => 'Classe Adolescentes',
            'is_active' => true,
        ]);
        $class->teachers()->attach($professor->id);

        $student1 = Student::create([
            'class_id' => $class->id,
            'name' => 'Aluno Um',
            'is_active' => true,
        ]);

        $student2 = Student::create([
            'class_id' => $class->id,
            'name' => 'Aluno Dois',
            'is_active' => true,
        ]);

        $today = now()->format('Y-m-d');

        Livewire::actingAs($professor)
            ->test(\App\Livewire\TakeAttendance::class, [
                'classId' => $class->id,
                'date' => $today,
            ])
            ->set('lessonNumber', 'Lição 05')
            ->set('lessonTitle', 'A Graça Redentora')
            ->set('attendances.' . $student1->id, true)
            ->set('attendances.' . $student2->id, false)
            ->set('visitorsCount', 4)
            ->set('biblesCount', 8)
            ->set('magazinesCount', 5)
            ->set('offeringsAmount', '35.50')
            ->set('observations', 'Aula produtiva')
            ->call('save')
            ->assertHasNoErrors();

        // Verify lesson record
        $this->assertDatabaseHas('lesson_records', [
            'class_id' => $class->id,
            'lesson_date' => $today,
            'lesson_number' => 'Lição 05',
            'visitors_count' => 4,
            'offerings_amount' => 35.50,
        ]);

        $record = LessonRecord::where('class_id', $class->id)->where('lesson_date', $today)->first();
        $this->assertNotNull($record);

        // Verify individual attendances
        $this->assertDatabaseHas('lesson_attendances', [
            'lesson_record_id' => $record->id,
            'student_id' => $student1->id,
            'is_present' => true,
        ]);

        $this->assertDatabaseHas('lesson_attendances', [
            'lesson_record_id' => $record->id,
            'student_id' => $student2->id,
            'is_present' => false,
        ]);

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'auditable_type' => LessonRecord::class,
            'auditable_id' => $record->id,
            'action' => 'ATTENDANCE_RECORDED',
        ]);
    }

    public function test_updating_attendance_on_same_date_updates_record_without_duplicate(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $class = EbdClass::create(['name' => 'Classe Adultos', 'is_active' => true]);
        $class->teachers()->attach($professor->id);

        $student = Student::create(['class_id' => $class->id, 'name' => 'Aluno', 'is_active' => true]);
        $today = now()->format('Y-m-d');

        // First submission
        Livewire::actingAs($professor)
            ->test(\App\Livewire\TakeAttendance::class, ['classId' => $class->id, 'date' => $today])
            ->set('attendances.' . $student->id, true)
            ->set('visitorsCount', 2)
            ->call('save');

        $this->assertEquals(1, LessonRecord::where('class_id', $class->id)->where('lesson_date', $today)->count());

        // Second submission (update on same day)
        Livewire::actingAs($professor)
            ->test(\App\Livewire\TakeAttendance::class, ['classId' => $class->id, 'date' => $today])
            ->set('visitorsCount', 5)
            ->call('save');

        // Must still be exactly 1 record, updated to 5 visitors
        $this->assertEquals(1, LessonRecord::where('class_id', $class->id)->where('lesson_date', $today)->count());
        $this->assertEquals(5, LessonRecord::where('class_id', $class->id)->where('lesson_date', $today)->first()->visitors_count);
    }
}
