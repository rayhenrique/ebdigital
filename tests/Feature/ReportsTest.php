<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\Reports\EbdReports;
use App\Models\EbdClass;
use App\Models\LessonAttendance;
use App\Models\LessonRecord;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_reports(): void
    {
        $response = $this->get(route('reports.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_professor_can_access_reports(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $response = $this->actingAs($professor)->get(route('reports.index'));
        $response->assertOk();
        $response->assertSee('Relatórios da Turma');
    }

    public function test_secretario_and_admin_can_access_reports(): void
    {
        $secretario = User::factory()->create([
            'role' => UserRole::SECRETARIO,
            'is_active' => true,
        ]);

        $response = $this->actingAs($secretario)->get(route('reports.index'));
        $response->assertOk();
        $response->assertSee('Relatórios da EBD');

        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $responseAdmin = $this->actingAs($admin)->get(route('reports.index'));
        $responseAdmin->assertOk();
    }

    public function test_professor_only_sees_their_assigned_classes_in_consolidated_report(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $myClass = EbdClass::create(['name' => 'Classe Adultos', 'is_active' => true]);
        $otherClass = EbdClass::create(['name' => 'Classe Juvenis', 'is_active' => true]);

        // Vincula o professor apenas à Classe Adultos
        $professor->teachingClasses()->attach($myClass->id);

        $myStudent = Student::create(['class_id' => $myClass->id, 'name' => 'Aluno Adulto', 'is_active' => true]);
        $otherStudent = Student::create(['class_id' => $otherClass->id, 'name' => 'Aluno Juvenil', 'is_active' => true]);

        $myRecord = LessonRecord::create([
            'class_id' => $myClass->id,
            'registered_by' => $professor->id,
            'lesson_date' => now()->format('Y-m-d'),
            'visitors_count' => 3,
            'bibles_count' => 5,
            'magazines_count' => 5,
            'offerings_amount' => 120.00,
        ]);
        LessonAttendance::create(['lesson_record_id' => $myRecord->id, 'student_id' => $myStudent->id, 'is_present' => true]);

        $otherRecord = LessonRecord::create([
            'class_id' => $otherClass->id,
            'registered_by' => $professor->id,
            'lesson_date' => now()->format('Y-m-d'),
            'visitors_count' => 10,
            'bibles_count' => 15,
            'magazines_count' => 15,
            'offerings_amount' => 450.00,
        ]);
        LessonAttendance::create(['lesson_record_id' => $otherRecord->id, 'student_id' => $otherStudent->id, 'is_present' => true]);

        Livewire::actingAs($professor)
            ->test(EbdReports::class)
            ->assertSee('Classe Adultos')
            ->assertDontSee('Classe Juvenis')
            ->assertSee('R$ 120,00')
            ->assertDontSee('R$ 450,00')
            ->assertDontSee('R$ 570,00');
    }

    public function test_professor_nominal_report_scoped_to_assigned_classes(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $myClass = EbdClass::create(['name' => 'Minha Turma', 'is_active' => true]);
        $otherClass = EbdClass::create(['name' => 'Outra Turma', 'is_active' => true]);

        $professor->teachingClasses()->attach($myClass->id);

        $myStudent = Student::create(['class_id' => $myClass->id, 'name' => 'Meu Aluno Dedicado', 'is_active' => true]);
        $otherStudent = Student::create(['class_id' => $otherClass->id, 'name' => 'Aluno Inacessivel', 'is_active' => true]);

        $record = LessonRecord::create([
            'class_id' => $myClass->id,
            'registered_by' => $professor->id,
            'lesson_date' => now()->format('Y-m-d'),
            'visitors_count' => 0,
            'bibles_count' => 1,
            'magazines_count' => 1,
            'offerings_amount' => 10.00,
        ]);
        LessonAttendance::create(['lesson_record_id' => $record->id, 'student_id' => $myStudent->id, 'is_present' => true]);

        Livewire::actingAs($professor)
            ->test(EbdReports::class)
            ->set('activeTab', 'students')
            ->assertSet('selectedClassId', $myClass->id)
            ->assertSee('Meu Aluno Dedicado')
            ->assertDontSee('Aluno Inacessivel')
            // Tentativa de alterar forçadamente para turma não autorizada
            ->set('selectedClassId', $otherClass->id)
            ->assertDontSee('Aluno Inacessivel');
    }

    public function test_professor_birthday_report_only_includes_students_from_assigned_classes(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $myClass = EbdClass::create(['name' => 'Classe Autorizada', 'is_active' => true]);
        $otherClass = EbdClass::create(['name' => 'Classe Oculta', 'is_active' => true]);

        $professor->teachingClasses()->attach($myClass->id);

        $myStudent = Student::create([
            'class_id' => $myClass->id,
            'name' => 'Aniversariante da Minha Sala',
            'birth_date' => '1995-05-10',
            'phone' => '82999991111',
            'is_active' => true,
        ]);

        $otherStudent = Student::create([
            'class_id' => $otherClass->id,
            'name' => 'Aniversariante de Outra Sala',
            'birth_date' => '1998-05-20',
            'phone' => '82999992222',
            'is_active' => true,
        ]);

        Livewire::actingAs($professor)
            ->test(EbdReports::class)
            ->set('activeTab', 'birthdays')
            ->set('selectedMonth', 5)
            ->assertSee('Aniversariante da Minha Sala')
            ->assertDontSee('Aniversariante de Outra Sala');
    }

    public function test_professor_with_no_assigned_classes_shows_empty_state_without_errors(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        Livewire::actingAs($professor)
            ->test(EbdReports::class)
            ->assertSee('Nenhuma sala vinculada')
            ->assertSet('activeTab', 'consolidated');
    }

    public function test_reports_component_calculates_consolidated_metrics(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $class = EbdClass::create(['name' => 'Classe Adultos', 'is_active' => true]);
        $student = Student::create(['class_id' => $class->id, 'name' => 'Aluno Presente', 'is_active' => true]);

        $record = LessonRecord::create([
            'class_id' => $class->id,
            'registered_by' => $admin->id,
            'lesson_date' => now()->format('Y-m-d'),
            'visitors_count' => 4,
            'bibles_count' => 8,
            'magazines_count' => 7,
            'offerings_amount' => 150.50,
        ]);

        LessonAttendance::create([
            'lesson_record_id' => $record->id,
            'student_id' => $student->id,
            'is_present' => true,
        ]);

        Livewire::actingAs($admin)
            ->test(EbdReports::class)
            ->assertSee('Classe Adultos')
            ->assertSee('R$ 150,50')
            ->assertSee('4') // visitantes
            ->assertSet('activeTab', 'consolidated');
    }

    public function test_period_shortcuts_update_dates_correctly(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $year = (int) now()->format('Y');

        Livewire::actingAs($admin)
            ->test(EbdReports::class)
            ->call('setShortcut', 'q1')
            ->assertSet('startDate', "{$year}-01-01")
            ->assertSet('endDate', "{$year}-03-31")
            ->call('setShortcut', 'q2')
            ->assertSet('startDate', "{$year}-04-01")
            ->assertSet('endDate', "{$year}-06-30")
            ->call('setShortcut', 'year')
            ->assertSet('startDate', "{$year}-01-01")
            ->assertSet('endDate', "{$year}-12-31");
    }

    public function test_birthday_report_finds_birthdays_in_month(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $class = EbdClass::create(['name' => 'Classe Jovens', 'is_active' => true]);

        $student = Student::create([
            'class_id' => $class->id,
            'name' => 'Aniversariante de Setembro',
            'birth_date' => '2000-09-15',
            'phone' => '82988887777',
            'is_active' => true,
        ]);

        Livewire::actingAs($admin)
            ->test(EbdReports::class)
            ->set('activeTab', 'birthdays')
            ->set('selectedMonth', 9)
            ->assertSee('Aniversariante de Setembro')
            ->assertSee('15/09')
            ->assertSee('Parabenizar (WhatsApp)');
    }
}
