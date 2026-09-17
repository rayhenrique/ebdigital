<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\DailyConsolidatedDashboard;
use App\Models\Congregation;
use App\Models\EbdClass;
use App\Models\LessonAttendance;
use App\Models\LessonRecord;
use App\Models\Student;
use App\Models\User;
use App\Services\TeacherNotificationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TeacherNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_sees_birthdays_today_and_in_upcoming_week_for_assigned_class(): void
    {
        Carbon::setTestNow('2026-09-20 10:00:00'); // Domingo

        $congregation = Congregation::firstOrCreate(
            ['name' => 'Sede Teste'],
            ['is_active' => true, 'is_headquarters' => true]
        );
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'congregation_id' => $congregation->id,
            'is_active' => true,
        ]);

        $myClass = EbdClass::create([
            'congregation_id' => $congregation->id,
            'name' => 'Classe Jovens Vencedores',
            'is_active' => true,
        ]);
        $myClass->teachers()->attach($professor);

        $otherClass = EbdClass::create([
            'congregation_id' => $congregation->id,
            'name' => 'Classe Adultos',
            'is_active' => true,
        ]);

        // Aluno da minha turma fazendo aniversário HOJE (20/09)
        $studentToday = Student::create([
            'class_id' => $myClass->id,
            'congregation_id' => $congregation->id,
            'name' => 'Lucas Hoje',
            'phone' => '82999991111',
            'birth_date' => '2000-09-20',
            'is_active' => true,
        ]);

        // Aluno da minha turma fazendo aniversário daqui a 3 dias (23/09)
        $studentWeek = Student::create([
            'class_id' => $myClass->id,
            'congregation_id' => $congregation->id,
            'name' => 'Mariana Semana',
            'phone' => '82999992222',
            'birth_date' => '2002-09-23',
            'is_active' => true,
        ]);

        // Aluno de OUTRA turma fazendo aniversário hoje (NÃO deve aparecer para o professor)
        $studentOtherClass = Student::create([
            'class_id' => $otherClass->id,
            'congregation_id' => $congregation->id,
            'name' => 'Carlos Outra Classe',
            'birth_date' => '1995-09-20',
            'is_active' => true,
        ]);

        $service = app(TeacherNotificationService::class);
        $notifications = $service->getNotifications($professor, '2026-09-20');

        $this->assertTrue($notifications['has_alerts']);
        $this->assertEquals(2, $notifications['total_alerts_count']);

        // Aniversariantes de Hoje
        $this->assertCount(1, $notifications['birthdays_today']);
        $this->assertEquals('Lucas Hoje', $notifications['birthdays_today'][0]['name']);
        $this->assertTrue($notifications['birthdays_today'][0]['is_today']);
        $this->assertStringContainsString('5582999991111', $notifications['birthdays_today'][0]['whatsapp_url']);

        // Aniversariantes da Semana (Hoje + 7 dias)
        $this->assertCount(2, $notifications['birthdays_week']);
        $namesWeek = collect($notifications['birthdays_week'])->pluck('name')->all();
        $this->assertContains('Lucas Hoje', $namesWeek);
        $this->assertContains('Mariana Semana', $namesWeek);
        $this->assertNotContains('Carlos Outra Classe', $namesWeek);

        Carbon::setTestNow();
    }

    public function test_teacher_sees_chronic_absentees_when_student_misses_last_three_lessons(): void
    {
        Carbon::setTestNow('2026-09-20 10:00:00');

        $congregation = Congregation::firstOrCreate(
            ['name' => 'Sede Teste'],
            ['is_active' => true, 'is_headquarters' => true]
        );
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'congregation_id' => $congregation->id,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'congregation_id' => $congregation->id,
            'name' => 'Classe Adolescentes',
            'is_active' => true,
        ]);
        $class->teachers()->attach($professor);

        // Criar 3 aulas anteriores
        $lesson1 = LessonRecord::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'registered_by' => $professor->id,
            'lesson_date' => '2026-08-30',
        ]);
        $lesson2 = LessonRecord::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'registered_by' => $professor->id,
            'lesson_date' => '2026-09-06',
        ]);
        $lesson3 = LessonRecord::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'registered_by' => $professor->id,
            'lesson_date' => '2026-09-13',
        ]);

        // Aluno A: Faltou às 3 aulas seguidas (Alerta de faltoso crônico!)
        $chronicStudent = Student::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'name' => 'Faltoso Cronico',
            'phone' => '82988887777',
            'is_active' => true,
        ]);
        LessonAttendance::create(['lesson_record_id' => $lesson1->id, 'student_id' => $chronicStudent->id, 'is_present' => false]);
        LessonAttendance::create(['lesson_record_id' => $lesson2->id, 'student_id' => $chronicStudent->id, 'is_present' => false]);
        LessonAttendance::create(['lesson_record_id' => $lesson3->id, 'student_id' => $chronicStudent->id, 'is_present' => false]);

        // Aluno B: Faltou 2 aulas, mas esteve presente na última (NÃO é crônico)
        $recoveringStudent = Student::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'name' => 'Aluno Recuperado',
            'is_active' => true,
        ]);
        LessonAttendance::create(['lesson_record_id' => $lesson1->id, 'student_id' => $recoveringStudent->id, 'is_present' => false]);
        LessonAttendance::create(['lesson_record_id' => $lesson2->id, 'student_id' => $recoveringStudent->id, 'is_present' => false]);
        LessonAttendance::create(['lesson_record_id' => $lesson3->id, 'student_id' => $recoveringStudent->id, 'is_present' => true]);

        // Aluno C: Faltou apenas 2 aulas recentes (NÃO é crônico ainda)
        $twoAbsentsStudent = Student::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'name' => 'Aluno Duas Faltas',
            'is_active' => true,
        ]);
        LessonAttendance::create(['lesson_record_id' => $lesson1->id, 'student_id' => $twoAbsentsStudent->id, 'is_present' => true]);
        LessonAttendance::create(['lesson_record_id' => $lesson2->id, 'student_id' => $twoAbsentsStudent->id, 'is_present' => false]);
        LessonAttendance::create(['lesson_record_id' => $lesson3->id, 'student_id' => $twoAbsentsStudent->id, 'is_present' => false]);

        $service = app(TeacherNotificationService::class);
        $notifications = $service->getNotifications($professor, '2026-09-20');

        $this->assertCount(1, $notifications['chronic_absentees']);
        $this->assertEquals('Faltoso Cronico', $notifications['chronic_absentees'][0]['name']);
        $this->assertEquals(3, $notifications['chronic_absentees'][0]['consecutive_absents']);
        $this->assertStringContainsString('5582988887777', $notifications['chronic_absentees'][0]['whatsapp_url']);

        Carbon::setTestNow();
    }

    public function test_dashboard_renders_notifications_panel_and_quick_action_buttons(): void
    {
        Carbon::setTestNow('2026-09-20 10:00:00');

        $congregation = Congregation::firstOrCreate(
            ['name' => 'Sede Teste'],
            ['is_active' => true, 'is_headquarters' => true]
        );
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'congregation_id' => $congregation->id,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'congregation_id' => $congregation->id,
            'name' => 'Classe Infantil',
            'is_active' => true,
        ]);
        $class->teachers()->attach($professor);

        // Aniversariante hoje
        Student::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'name' => 'Bento Aniversariante',
            'phone' => '82991112222',
            'birth_date' => '2018-09-20',
            'is_active' => true,
        ]);

        // 3 aulas com falta
        $l1 = LessonRecord::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'registered_by' => $professor->id,
            'lesson_date' => '2026-08-30',
        ]);
        $l2 = LessonRecord::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'registered_by' => $professor->id,
            'lesson_date' => '2026-09-06',
        ]);
        $l3 = LessonRecord::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'registered_by' => $professor->id,
            'lesson_date' => '2026-09-13',
        ]);

        $absentStudent = Student::create([
            'class_id' => $class->id,
            'congregation_id' => $congregation->id,
            'name' => 'Sofia Faltosa',
            'phone' => '82993334444',
            'is_active' => true,
        ]);
        LessonAttendance::create(['lesson_record_id' => $l1->id, 'student_id' => $absentStudent->id, 'is_present' => false]);
        LessonAttendance::create(['lesson_record_id' => $l2->id, 'student_id' => $absentStudent->id, 'is_present' => false]);
        LessonAttendance::create(['lesson_record_id' => $l3->id, 'student_id' => $absentStudent->id, 'is_present' => false]);

        $this->actingAs($professor);

        Livewire::test(DailyConsolidatedDashboard::class, ['date' => '2026-09-20'])
            ->assertSee('Lembretes')
            ->assertSee('Cuidado Pastoral')
            ->assertSee('Bento Aniversariante')
            ->assertSee('Hoje! 🎉')
            ->assertSee('Felicitar WhatsApp')
            ->assertSee('Sofia Faltosa')
            ->assertSee('3 faltas seguidas');

        // Testar requisição HTTP na rota /dashboard
        $response = $this->get(route('dashboard'));
        $response->assertOk();
        $response->assertSee('id="lembretes-turma"', false);
        $response->assertSee('Bento Aniversariante');
        $response->assertSee('Sofia Faltosa');

        Carbon::setTestNow();
    }

    public function test_teacher_without_alerts_sees_positive_empty_state(): void
    {
        $congregation = Congregation::firstOrCreate(
            ['name' => 'Sede Teste'],
            ['is_active' => true, 'is_headquarters' => true]
        );
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'congregation_id' => $congregation->id,
            'is_active' => true,
        ]);

        $class = EbdClass::create([
            'congregation_id' => $congregation->id,
            'name' => 'Classe Regular',
            'is_active' => true,
        ]);
        $class->teachers()->attach($professor);

        $this->actingAs($professor);

        Livewire::test(DailyConsolidatedDashboard::class)
            ->assertSee('Tudo em dia com a sua turma!')
            ->assertSee('Nenhum aniversariante nesta semana e nenhum aluno com 3 faltas consecutivas.');
    }
}
