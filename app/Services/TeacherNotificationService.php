<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EbdClass;
use App\Models\LessonAttendance;
use App\Models\LessonRecord;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TeacherNotificationService
{
    /**
     * Retorna os dados de notificações e lembretes (aniversariantes e faltosos crônicos)
     * para as turmas acessíveis ao usuário fornecido.
     *
     * @return array{
     *     birthdays_today: array<int, array<string, mixed>>,
     *     birthdays_week: array<int, array<string, mixed>>,
     *     birthdays_month: array<int, array<string, mixed>>,
     *     chronic_absentees: array<int, array<string, mixed>>,
     *     total_alerts_count: int,
     *     has_alerts: bool
     * }
     */
    public function getNotifications(User $user, ?string $referenceDate = null): array
    {
        $ref = Carbon::parse($referenceDate ?? now()->format('Y-m-d'))->startOfDay();

        // 1. Identificar turmas autorizadas
        if ($user->isProfessor()) {
            $classes = $user->teachingClasses()
                ->where('classes.is_active', true)
                ->with(['teachers'])
                ->get();
        } else {
            $classes = EbdClass::active()
                ->with(['teachers'])
                ->get();
        }

        if ($classes->isEmpty()) {
            return [
                'birthdays_today' => [],
                'birthdays_week' => [],
                'birthdays_month' => [],
                'chronic_absentees' => [],
                'total_alerts_count' => 0,
                'has_alerts' => false,
            ];
        }

        $classIds = $classes->pluck('id')->all();

        // 2. Buscar alunos ativos das turmas
        $students = Student::active()
            ->whereIn('class_id', $classIds)
            ->with('ebdClass')
            ->orderBy('name')
            ->get();

        // 3. Processar Aniversariantes
        $birthdaysToday = [];
        $birthdaysWeek = [];
        $birthdaysMonth = [];

        foreach ($students as $student) {
            if (! $student->birth_date) {
                continue;
            }

            $birth = Carbon::parse($student->birth_date);
            $bDay = (int) $birth->format('d');
            $bMonth = (int) $birth->format('m');

            // Ajuste para 29 de fevereiro em anos não bissextos
            $thisYearDay = $bDay;
            $isLeapThisYear = Carbon::create($ref->year, 1, 1)->isLeapYear();
            if ($bMonth === 2 && $bDay === 29 && ! $isLeapThisYear) {
                $thisYearDay = 28;
            }

            $thisYearBirthday = Carbon::create($ref->year, $bMonth, $thisYearDay)->startOfDay();

            if ($thisYearBirthday->lt($ref)) {
                $nextYearDay = $bDay;
                $isLeapNextYear = Carbon::create($ref->year + 1, 1, 1)->isLeapYear();
                if ($bMonth === 2 && $bDay === 29 && ! $isLeapNextYear) {
                    $nextYearDay = 28;
                }
                $nextBirthday = Carbon::create($ref->year + 1, $bMonth, $nextYearDay)->startOfDay();
            } else {
                $nextBirthday = $thisYearBirthday;
            }

            $daysUntil = (int) $ref->diffInDays($nextBirthday, false);
            $isToday = ($bDay === (int) $ref->format('d') && $bMonth === (int) $ref->format('m'));
            $isThisMonth = ($bMonth === (int) $ref->format('m'));
            $isUpcoming7Days = ($daysUntil >= 0 && $daysUntil <= 7);
            $age = $birth->age;

            // Formatação do link WhatsApp
            $whatsappUrl = $this->generateBirthdayWhatsAppUrl(
                name: $student->name,
                phone: $student->phone,
                className: $student->ebdClass?->name ?? 'EBD'
            );

            $birthdayItem = [
                'id' => $student->id,
                'name' => $student->name,
                'class_id' => $student->class_id,
                'class_name' => $student->ebdClass?->name ?? 'Sem classe',
                'birth_date' => $student->birth_date->format('Y-m-d'),
                'day' => $bDay,
                'formatted_day' => sprintf('%02d/%02d', $bDay, $bMonth),
                'age' => $age,
                'days_until' => $daysUntil,
                'is_today' => $isToday,
                'phone' => $student->phone,
                'whatsapp_url' => $whatsappUrl,
            ];

            if ($isToday) {
                $birthdaysToday[] = $birthdayItem;
            }

            if ($isUpcoming7Days) {
                $birthdaysWeek[] = $birthdayItem;
            }

            if ($isThisMonth) {
                $birthdaysMonth[] = $birthdayItem;
            }
        }

        // Ordenar aniversariantes da semana por proximidade (dias restantes)
        usort($birthdaysWeek, fn (array $a, array $b) => $a['days_until'] <=> $b['days_until']);
        // Ordenar aniversariantes do mês por dia do mês
        usort($birthdaysMonth, fn (array $a, array $b) => $a['day'] <=> $b['day']);

        // 4. Processar Alunos Faltosos Crônicos (3+ faltas consecutivas)
        $chronicAbsentees = [];

        // Buscar últimas 10 aulas de cada turma até a data de referência
        $lessonRecords = LessonRecord::whereIn('class_id', $classIds)
            ->where('lesson_date', '<=', $ref->format('Y-m-d'))
            ->orderBy('lesson_date', 'desc')
            ->get()
            ->groupBy('class_id');

        $allRecordIds = $lessonRecords->flatten()->pluck('id')->all();
        $attendances = ! empty($allRecordIds)
            ? LessonAttendance::whereIn('lesson_record_id', $allRecordIds)->get()
            : collect();

        foreach ($classes as $class) {
            /** @var Collection<int, LessonRecord> $classRecords */
            $classRecords = $lessonRecords->get($class->id, collect());

            // Só pode haver 3 faltas consecutivas se a classe tiver pelo menos 3 aulas registradas
            if ($classRecords->count() < 3) {
                continue;
            }

            // Ordenar em ordem cronológica (mais antiga para a mais recente)
            $classRecordsAsc = $classRecords->take(10)->reverse()->values();

            $classStudents = $students->where('class_id', $class->id);

            foreach ($classStudents as $student) {
                $studentAtts = $attendances->where('student_id', $student->id);
                $currentConsecutive = 0;
                $lastMissedDate = null;

                foreach ($classRecordsAsc as $record) {
                    $att = $studentAtts->firstWhere('lesson_record_id', $record->id);
                    $isPresent = $att ? (bool) $att->is_present : false;

                    if (! $isPresent) {
                        $currentConsecutive++;
                        $lastMissedDate = $record->lesson_date;
                    } else {
                        $currentConsecutive = 0;
                        $lastMissedDate = null;
                    }
                }

                if ($currentConsecutive >= 3) {
                    $whatsappUrl = $this->generatePastoralWhatsAppUrl(
                        name: $student->name,
                        phone: $student->phone,
                        className: $class->name
                    );

                    $chronicAbsentees[] = [
                        'id' => $student->id,
                        'name' => $student->name,
                        'class_id' => $student->class_id,
                        'class_name' => $class->name,
                        'phone' => $student->phone,
                        'whatsapp_url' => $whatsappUrl,
                        'consecutive_absents' => $currentConsecutive,
                        'last_missed_date' => $lastMissedDate ? Carbon::parse($lastMissedDate)->format('d/m/Y') : null,
                    ];
                }
            }
        }

        // Ordenar faltosos crônicos por maior número de faltas consecutivas primeiro
        usort($chronicAbsentees, function (array $a, array $b) {
            $cmp = $b['consecutive_absents'] <=> $a['consecutive_absents'];
            return $cmp !== 0 ? $cmp : strcmp($a['name'], $b['name']);
        });

        $totalAlertsCount = count($birthdaysWeek) + count($chronicAbsentees);

        return [
            'birthdays_today' => $birthdaysToday,
            'birthdays_week' => $birthdaysWeek,
            'birthdays_month' => $birthdaysMonth,
            'chronic_absentees' => $chronicAbsentees,
            'total_alerts_count' => $totalAlertsCount,
            'has_alerts' => $totalAlertsCount > 0,
        ];
    }

    /**
     * Gera URL formatada do WhatsApp para felicitação de aniversário.
     */
    public function generateBirthdayWhatsAppUrl(string $name, ?string $phone, string $className): ?string
    {
        $cleanPhone = preg_replace('/\D/', '', (string) $phone);
        if (! $cleanPhone || strlen($cleanPhone) < 10) {
            return null;
        }

        if (! str_starts_with($cleanPhone, '55')) {
            $cleanPhone = '55' . $cleanPhone;
        }

        $message = "A paz do Senhor, irmão(ã) {$name}! 🎉 A liderança da EBD e a sua classe ({$className}) parabenizam você pelo seu aniversário! Que Deus derrame ricas bênçãos sobre sua vida, com muita saúde, paz e graça!";

        return 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($message);
    }

    /**
     * Gera URL formatada do WhatsApp para contato de cuidado pastoral (faltoso crônico).
     */
    public function generatePastoralWhatsAppUrl(string $name, ?string $phone, string $className): ?string
    {
        $cleanPhone = preg_replace('/\D/', '', (string) $phone);
        if (! $cleanPhone || strlen($cleanPhone) < 10) {
            return null;
        }

        if (! str_starts_with($cleanPhone, '55')) {
            $cleanPhone = '55' . $cleanPhone;
        }

        $message = "A paz do Senhor, irmão(ã) {$name}! Sentimos muito a sua falta na nossa Escola Bíblica Dominical ({$className}) nos últimos domingos! Está tudo bem com você e sua família? Gostaríamos de orar por você. Qualquer coisa que precisar conte conosco. Esperamos você no próximo domingo!";

        return 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($message);
    }
}
