<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\EbdClass;
use App\Models\LessonRecord;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class DailyConsolidatedDashboard extends Component
{
    public string $selectedDate;

    public function mount(?string $date = null): void
    {
        $this->selectedDate = $date ?? now()->format('Y-m-d');
    }

    public function updatedSelectedDate(): void
    {
        // Automatically re-renders with new date
    }

    public function render(): View
    {
        $classes = EbdClass::active()
            ->with(['teachers', 'activeStudents'])
            ->orderBy('name')
            ->get();

        $records = LessonRecord::with(['attendances', 'registeredBy'])
            ->where('lesson_date', $this->selectedDate)
            ->get()
            ->keyBy('class_id');

        // Aggregations
        $totalEnrolled = 0;
        $totalPresent = 0;
        $totalVisitors = 0;
        $totalBibles = 0;
        $totalMagazines = 0;
        $totalOfferings = 0.0;
        $deliveredClassesCount = 0;

        $classReports = [];

        foreach ($classes as $class) {
            $record = $records[$class->id] ?? null;
            $enrolled = $class->activeStudents->count();
            $totalEnrolled += $enrolled;

            if ($record) {
                $deliveredClassesCount++;
                $present = $record->attendances->where('is_present', true)->count();
                $absent = max(0, $record->attendances->count() - $present);
                $visitors = (int) $record->visitors_count;
                $bibles = (int) $record->bibles_count;
                $magazines = (int) $record->magazines_count;
                $offerings = (float) $record->offerings_amount;

                $totalPresent += $present;
                $totalVisitors += $visitors;
                $totalBibles += $bibles;
                $totalMagazines += $magazines;
                $totalOfferings += $offerings;

                $rate = $enrolled > 0 ? (int) round(($present / $enrolled) * 100) : 0;

                $classReports[] = [
                    'class' => $class,
                    'record' => $record,
                    'is_delivered' => true,
                    'enrolled' => $enrolled,
                    'present' => $present,
                    'absent' => $absent,
                    'visitors' => $visitors,
                    'total_attendance' => $present + $visitors,
                    'bibles' => $bibles,
                    'magazines' => $magazines,
                    'offerings' => $offerings,
                    'rate' => $rate,
                    'registered_by' => $record->registeredBy?->name ?? 'N/A',
                ];
            } else {
                $classReports[] = [
                    'class' => $class,
                    'record' => null,
                    'is_delivered' => false,
                    'enrolled' => $enrolled,
                    'present' => 0,
                    'absent' => $enrolled,
                    'visitors' => 0,
                    'total_attendance' => 0,
                    'bibles' => 0,
                    'magazines' => 0,
                    'offerings' => 0.0,
                    'rate' => 0,
                    'registered_by' => null,
                ];
            }
        }

        $overallRate = $totalEnrolled > 0 ? (int) round(($totalPresent / $totalEnrolled) * 100) : 0;
        $totalCongregation = $totalPresent + $totalVisitors;

        return view('livewire.daily-consolidated-dashboard', [
            'classReports' => $classReports,
            'classesCount' => $classes->count(),
            'deliveredClassesCount' => $deliveredClassesCount,
            'totalEnrolled' => $totalEnrolled,
            'totalPresent' => $totalPresent,
            'totalAbsent' => max(0, $totalEnrolled - $totalPresent),
            'totalVisitors' => $totalVisitors,
            'totalCongregation' => $totalCongregation,
            'totalBibles' => $totalBibles,
            'totalMagazines' => $totalMagazines,
            'totalOfferings' => $totalOfferings,
            'overallRate' => $overallRate,
        ]);
    }
}
