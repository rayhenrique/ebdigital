<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Models\EbdClass;
use App\Models\LessonAttendance;
use App\Models\LessonRecord;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class EbdReports extends Component
{
    public string $activeTab = 'consolidated'; // 'consolidated', 'students', 'birthdays'

    // Filtros de Período
    public string $startDate;
    public string $endDate;
    public string $currentShortcut = 'month';

    // Filtro por Classe na aba de Alunos
    public ?int $selectedClassId = null;

    // Filtro de Aniversariantes
    public int $selectedMonth;
    public ?int $birthdayClassId = null;

    public function mount(): void
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->currentShortcut = 'month';
        $this->selectedMonth = (int) now()->format('n');

        $firstClass = $this->getAllowedClasses()->first();
        if ($firstClass) {
            $this->selectedClassId = $firstClass->id;
        }
    }

    /**
     * Retorna a coleção de classes permitidas para o usuário logado.
     * Professores veem estritamente as classes em que lecionam.
     * Secretários e Administradores veem as classes da congregação.
     *
     * @return Collection<int, EbdClass>
     */
    protected function getAllowedClasses(): Collection
    {
        $user = Auth::user();

        if ($user && $user->isProfessor()) {
            return $user->teachingClasses()
                ->where('classes.is_active', true)
                ->orderBy('classes.name')
                ->get();
        }

        return EbdClass::active()->orderBy('name')->get();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function setShortcut(string $shortcut): void
    {
        $year = (int) now()->format('Y');
        $this->currentShortcut = $shortcut;

        switch ($shortcut) {
            case 'month':
                $this->startDate = now()->startOfMonth()->format('Y-m-d');
                $this->endDate = now()->endOfMonth()->format('Y-m-d');
                break;
            case 'q1':
                $this->startDate = Carbon::create($year, 1, 1)->format('Y-m-d');
                $this->endDate = Carbon::create($year, 3, 31)->format('Y-m-d');
                break;
            case 'q2':
                $this->startDate = Carbon::create($year, 4, 1)->format('Y-m-d');
                $this->endDate = Carbon::create($year, 6, 30)->format('Y-m-d');
                break;
            case 'q3':
                $this->startDate = Carbon::create($year, 7, 1)->format('Y-m-d');
                $this->endDate = Carbon::create($year, 9, 30)->format('Y-m-d');
                break;
            case 'q4':
                $this->startDate = Carbon::create($year, 10, 1)->format('Y-m-d');
                $this->endDate = Carbon::create($year, 12, 31)->format('Y-m-d');
                break;
            case 'year':
                $this->startDate = Carbon::create($year, 1, 1)->format('Y-m-d');
                $this->endDate = Carbon::create($year, 12, 31)->format('Y-m-d');
                break;
            default:
                break;
        }
    }

    public function updatedStartDate(): void
    {
        $this->currentShortcut = 'custom';
    }

    public function updatedEndDate(): void
    {
        $this->currentShortcut = 'custom';
    }

    public function render(): View
    {
        $classes = $this->getAllowedClasses();
        $allowedClassIds = $classes->pluck('id')->all();

        // Validar que a classe selecionada pertence às classes permitidas
        if ($this->selectedClassId !== null && !in_array($this->selectedClassId, $allowedClassIds, true)) {
            $this->selectedClassId = $classes->first()?->id;
        } elseif ($this->selectedClassId === null && $classes->isNotEmpty()) {
            $this->selectedClassId = $classes->first()->id;
        }

        if ($this->birthdayClassId !== null && !in_array($this->birthdayClassId, $allowedClassIds, true)) {
            $this->birthdayClassId = null;
        }

        return view('livewire.reports.ebd-reports', [
            'ebdClasses' => $classes,
            'classes' => $classes,
            'consolidatedData' => $this->getConsolidatedReportData($allowedClassIds),
            'nominalData' => $this->getNominalStudentsReportData($allowedClassIds),
            'birthdayData' => $this->getBirthdayReportData($allowedClassIds),
        ]);
    }

    /**
     * Calcula as métricas consolidadas gerais e por classe no período selecionado.
     *
     * @param list<int> $allowedClassIds
     */
    protected function getConsolidatedReportData(array $allowedClassIds): array
    {
        $user = Auth::user();
        $isProfessor = $user && $user->isProfessor();

        // Se professor não tiver turmas vinculadas, retorna métricas zeradas
        if ($isProfessor && empty($allowedClassIds)) {
            return [
                'unique_sundays' => 0,
                'total_present' => 0,
                'avg_present_per_sunday' => 0,
                'overall_rate' => 0,
                'total_visitors' => 0,
                'total_bibles' => 0,
                'total_magazines' => 0,
                'total_offerings' => 0.0,
                'classes_breakdown' => [],
            ];
        }

        $recordsQuery = LessonRecord::with(['attendances', 'ebdClass.teachers'])
            ->whereBetween('lesson_date', [$this->startDate, $this->endDate]);

        if ($isProfessor) {
            $recordsQuery->whereIn('class_id', $allowedClassIds);
        }

        $records = $recordsQuery->orderBy('lesson_date')->get();

        $uniqueSundaysCount = $records->pluck('lesson_date')->unique()->count();

        $studentsQuery = Student::active();
        if ($isProfessor) {
            $studentsQuery->whereIn('class_id', $allowedClassIds);
        }
        $totalEnrolledActive = $studentsQuery->count();
        $totalPresentCount = 0;
        $totalVisitorsCount = 0;
        $totalBiblesCount = 0;
        $totalMagazinesCount = 0;
        $totalOfferingsAmount = 0.0;

        $classAggregates = [];

        foreach ($records as $record) {
            $classId = $record->class_id;
            $presents = $record->attendances->where('is_present', true)->count();
            $visitors = (int) $record->visitors_count;
            $bibles = (int) $record->bibles_count;
            $magazines = (int) $record->magazines_count;
            $offerings = (float) $record->offerings_amount;

            $totalPresentCount += $presents;
            $totalVisitorsCount += $visitors;
            $totalBiblesCount += $bibles;
            $totalMagazinesCount += $magazines;
            $totalOfferingsAmount += $offerings;

            if (! isset($classAggregates[$classId])) {
                $classAggregates[$classId] = [
                    'class' => $record->ebdClass,
                    'lessons_count' => 0,
                    'presents_sum' => 0,
                    'visitors_sum' => 0,
                    'bibles_sum' => 0,
                    'magazines_sum' => 0,
                    'offerings_sum' => 0.0,
                ];
            }

            $classAggregates[$classId]['lessons_count']++;
            $classAggregates[$classId]['presents_sum'] += $presents;
            $classAggregates[$classId]['visitors_sum'] += $visitors;
            $classAggregates[$classId]['bibles_sum'] += $bibles;
            $classAggregates[$classId]['magazines_sum'] += $magazines;
            $classAggregates[$classId]['offerings_sum'] += $offerings;
        }

        // Formatação final da tabela por classe
        $classesTable = [];
        foreach ($classAggregates as $item) {
            $ebdClass = $item['class'];
            $lessonsCount = $item['lessons_count'];
            $enrolled = $ebdClass ? $ebdClass->students()->where('is_active', true)->count() : 0;
            $avgPresent = $lessonsCount > 0 ? round($item['presents_sum'] / $lessonsCount, 1) : 0;
            $rate = ($enrolled > 0 && $lessonsCount > 0)
                ? (int) round(($item['presents_sum'] / ($enrolled * $lessonsCount)) * 100)
                : 0;

            $classesTable[] = [
                'name' => $ebdClass?->name ?? 'Classe Removida',
                'teachers' => $ebdClass ? $ebdClass->teachers->pluck('name')->join(', ') : 'N/A',
                'enrolled' => $enrolled,
                'lessons_count' => $lessonsCount,
                'presents_sum' => $item['presents_sum'],
                'avg_present' => $avgPresent,
                'visitors_sum' => $item['visitors_sum'],
                'offerings_sum' => $item['offerings_sum'],
                'rate' => min(100, $rate),
            ];
        }

        usort($classesTable, fn ($a, $b) => strcmp($a['name'], $b['name']));

        $avgAttendancePerSunday = $uniqueSundaysCount > 0
            ? round($totalPresentCount / $uniqueSundaysCount, 1)
            : 0;

        $overallRate = ($totalEnrolledActive > 0 && $uniqueSundaysCount > 0)
            ? (int) round(($totalPresentCount / ($totalEnrolledActive * $uniqueSundaysCount)) * 100)
            : 0;

        return [
            'unique_sundays' => $uniqueSundaysCount,
            'total_present' => $totalPresentCount,
            'avg_present_per_sunday' => $avgAttendancePerSunday,
            'overall_rate' => min(100, $overallRate),
            'total_visitors' => $totalVisitorsCount,
            'total_bibles' => $totalBiblesCount,
            'total_magazines' => $totalMagazinesCount,
            'total_offerings' => $totalOfferingsAmount,
            'classes_breakdown' => $classesTable,
        ];
    }

    /**
     * Calcula o relatório nominal de frequência e alertas de faltosos por aluno na classe.
     *
     * @param list<int> $allowedClassIds
     */
    protected function getNominalStudentsReportData(array $allowedClassIds): array
    {
        if (! $this->selectedClassId || ! in_array($this->selectedClassId, $allowedClassIds, true)) {
            return [
                'selected_class' => null,
                'lesson_dates' => [],
                'students_rows' => [],
                'chronic_absents_count' => 0,
            ];
        }

        $class = EbdClass::with('teachers')->find($this->selectedClassId);
        if (! $class) {
            return [
                'selected_class' => null,
                'lesson_dates' => [],
                'students_rows' => [],
                'chronic_absents_count' => 0,
            ];
        }

        $records = LessonRecord::where('class_id', $this->selectedClassId)
            ->whereBetween('lesson_date', [$this->startDate, $this->endDate])
            ->orderBy('lesson_date')
            ->get();

        $lessonDates = $records->pluck('lesson_date')->map(fn ($d) => Carbon::parse($d)->format('d/m'))->toArray();
        $recordIds = $records->pluck('id')->toArray();

        $students = Student::where('class_id', $this->selectedClassId)
            ->orderBy('name')
            ->get();

        // Carregar presenças desses registros
        $attendances = LessonAttendance::whereIn('lesson_record_id', $recordIds)->get();

        $totalLessons = count($records);
        $studentsRows = [];
        $chronicCount = 0;

        foreach ($students as $student) {
            $studentAtts = $attendances->where('student_id', $student->id);
            $presentsCount = $studentAtts->where('is_present', true)->count();
            $absentsCount = $totalLessons - $presentsCount;
            $rate = $totalLessons > 0 ? (int) round(($presentsCount / $totalLessons) * 100) : 0;

            // Histórico ordenado de presença (P / F)
            $history = [];
            $consecutiveAbsents = 0;
            $currentConsecutive = 0;

            foreach ($records as $rec) {
                $att = $studentAtts->firstWhere('lesson_record_id', $rec->id);
                $isPresent = $att ? (bool) $att->is_present : false;
                $history[] = $isPresent ? 'P' : 'F';

                if (! $isPresent) {
                    $currentConsecutive++;
                } else {
                    $currentConsecutive = 0;
                }
            }

            // Alerta se as últimas 3 aulas forem faltas consecutivas (ou 3 seguidas no período)
            $isChronicAbsent = ($currentConsecutive >= 3);
            if ($isChronicAbsent) {
                $chronicCount++;
            }

            $studentsRows[] = [
                'id' => $student->id,
                'name' => $student->name,
                'phone' => $student->phone,
                'is_active' => $student->is_active,
                'presents' => $presentsCount,
                'absents' => $absentsCount,
                'rate' => $rate,
                'history' => $history,
                'is_chronic_absent' => $isChronicAbsent,
                'consecutive_absents' => $currentConsecutive,
            ];
        }

        return [
            'selected_class' => $class,
            'lesson_dates' => $lessonDates,
            'students_rows' => $studentsRows,
            'total_lessons' => $totalLessons,
            'chronic_absents_count' => $chronicCount,
        ];
    }

    /**
     * Retorna a listagem de aniversariantes do mês selecionado.
     *
     * @param list<int> $allowedClassIds
     */
    protected function getBirthdayReportData(array $allowedClassIds): array
    {
        $user = Auth::user();
        $isProfessor = $user && $user->isProfessor();
        $month = $this->selectedMonth;

        $monthNames = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
        ];

        // Se professor não tiver turmas vinculadas, retorna lista vazia segura
        if ($isProfessor && empty($allowedClassIds)) {
            return [
                'month_name' => $monthNames[$month] ?? 'Mês',
                'birthdays' => [],
                'total' => 0,
            ];
        }

        $query = Student::with('ebdClass')
            ->whereNotNull('birth_date')
            ->whereMonth('birth_date', $month);

        if ($isProfessor) {
            if ($this->birthdayClassId && in_array($this->birthdayClassId, $allowedClassIds, true)) {
                $query->where('class_id', $this->birthdayClassId);
            } else {
                $query->whereIn('class_id', $allowedClassIds);
            }
        } elseif ($this->birthdayClassId) {
            $query->where('class_id', $this->birthdayClassId);
        }

        $students = $query->get()->map(function ($student) {
            $birth = Carbon::parse($student->birth_date);
            $day = (int) $birth->format('d');
            $age = $birth->age;

            // Formatação do link de WhatsApp
            $cleanPhone = preg_replace('/\D/', '', (string) $student->phone);
            $whatsappUrl = null;
            if ($cleanPhone && strlen($cleanPhone) >= 10) {
                $cleanPhone = str_starts_with($cleanPhone, '55') ? $cleanPhone : '55' . $cleanPhone;
                $message = urlencode("A paz do Senhor, irmão(ã) {$student->name}! A liderança da Escola Bíblica Dominical parabeniza você pelo seu aniversário! Que Deus continue te abençoando ricamente!");
                $whatsappUrl = "https://wa.me/{$cleanPhone}?text={$message}";
            }

            return [
                'name' => $student->name,
                'day' => $day,
                'formatted_day' => sprintf('%02d/%02d', $day, $this->selectedMonth),
                'class_name' => $student->ebdClass?->name ?? 'Sem classe',
                'phone' => $student->phone,
                'whatsapp_url' => $whatsappUrl,
                'age' => $age,
            ];
        })->sortBy('day')->values()->toArray();

        return [
            'month_name' => $monthNames[$month] ?? 'Mês',
            'birthdays' => $students,
            'total' => count($students),
        ];
    }
}
