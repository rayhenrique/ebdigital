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

    public function test_professor_cannot_access_reports(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $response = $this->actingAs($professor)->get(route('reports.index'));
        $response->assertForbidden();
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
