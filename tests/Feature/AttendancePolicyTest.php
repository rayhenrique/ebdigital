<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\EbdClass;
use App\Models\LessonRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendancePolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_professor_cannot_save_attendance_on_past_date(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'name' => 'Classe Jovens',
            'is_active' => true,
        ]);

        $class->teachers()->attach($professor->id);

        $pastDate = now()->subDays(7)->format('Y-m-d');

        // Test Livewire component rejection for past date by teacher
        \Livewire\Livewire::actingAs($professor)
            ->test(\App\Livewire\TakeAttendance::class, [
                'classId' => $class->id,
                'date' => $pastDate,
            ])
            ->assertSet('isReadOnly', true)
            ->call('save')
            ->assertHasErrors(['lessonDate']);
    }

    public function test_professor_cannot_access_class_they_do_not_teach(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'name' => 'Classe Adultos',
            'is_active' => true,
        ]);

        // Professor is NOT attached to this class
        $response = $this->actingAs($professor)->get(route('chamada.take', ['class' => $class->id]));
        $response->assertForbidden();
    }

    public function test_secretario_can_save_attendance_on_past_date(): void
    {
        $secretario = User::factory()->create([
            'role' => UserRole::SECRETARIO,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'name' => 'Classe Adultos',
            'is_active' => true,
        ]);

        $student = \App\Models\Student::create([
            'class_id' => $class->id,
            'name' => 'Aluno Teste',
            'is_active' => true,
        ]);

        $pastDate = now()->subDays(14)->format('Y-m-d');

        \Livewire\Livewire::actingAs($secretario)
            ->test(\App\Livewire\TakeAttendance::class, [
                'classId' => $class->id,
                'date' => $pastDate,
            ])
            ->assertSet('isReadOnly', false)
            ->set('attendances.' . $student->id, true)
            ->set('visitorsCount', 3)
            ->set('offeringsAmount', '50.00')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('lesson_records', [
            'class_id' => $class->id,
            'lesson_date' => $pastDate,
            'visitors_count' => 3,
        ]);
    }
}
