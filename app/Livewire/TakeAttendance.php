<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\EbdClass;
use App\Models\LessonAttendance;
use App\Models\LessonRecord;
use App\Models\Student;
use App\Services\AuditService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class TakeAttendance extends Component
{
    public int $classId;
    public string $lessonDate;
    public ?string $lessonNumber = null;
    public ?string $lessonTitle = null;
    public int|string|null $visitorsCount = 0;
    public int|string|null $biblesCount = 0;
    public int|string|null $magazinesCount = 0;
    public int|string|null $offeringsAmount = '0.00';
    public ?string $observations = null;

    /**
     * Array of student attendances: [student_id => bool]
     *
     * @var array<int, bool>
     */
    public array $attendances = [];

    public bool $isReadOnly = false;
    public ?int $existingRecordId = null;

    public function mount(int $classId, ?string $date = null): void
    {
        $this->classId = $classId;
        $this->lessonDate = $date ?? now()->format('Y-m-d');

        $this->authorizeAccess();
        $this->loadLessonData();
    }

    public function updatedLessonDate(): void
    {
        $this->authorizeAccess();
        $this->loadLessonData();
    }

    protected function authorizeAccess(): void
    {
        $user = Auth::user();
        $class = EbdClass::findOrFail($this->classId);

        // If professor, verify assignment
        if ($user->isProfessor()) {
            $isAssigned = $user->teachingClasses()->where('classes.id', $this->classId)->exists();
            if (!$isAssigned) {
                abort(403, 'Você não possui permissão para acessar a chamada desta classe.');
            }

            // If date is not today, teacher can only view (read-only)
            $isToday = Carbon::parse($this->lessonDate)->isToday();
            $this->isReadOnly = !$isToday;
        } else {
            $this->isReadOnly = false;
        }
    }

    public function loadLessonData(): void
    {
        $class = EbdClass::with(['activeStudents'])->findOrFail($this->classId);

        $existingRecord = LessonRecord::with('attendances')
            ->where('class_id', $this->classId)
            ->where('lesson_date', $this->lessonDate)
            ->first();

        if ($existingRecord) {
            $this->existingRecordId = $existingRecord->id;
            $this->lessonNumber = $existingRecord->lesson_number;
            $this->lessonTitle = $existingRecord->lesson_title;
            $this->visitorsCount = (int) $existingRecord->visitors_count;
            $this->biblesCount = (int) $existingRecord->bibles_count;
            $this->magazinesCount = (int) $existingRecord->magazines_count;
            $this->offeringsAmount = number_format((float) $existingRecord->offerings_amount, 2, '.', '');
            $this->observations = $existingRecord->observations;

            // Map saved attendances
            $this->attendances = [];
            foreach ($existingRecord->attendances as $att) {
                $this->attendances[$att->student_id] = (bool) $att->is_present;
            }

            // Also make sure any newly added active students appear
            foreach ($class->activeStudents as $student) {
                if (!isset($this->attendances[$student->id])) {
                    $this->attendances[$student->id] = false;
                }
            }
        } else {
            $this->existingRecordId = null;
            $this->lessonNumber = null;
            $this->lessonTitle = null;
            $this->visitorsCount = 0;
            $this->biblesCount = 0;
            $this->magazinesCount = 0;
            $this->offeringsAmount = '0.00';
            $this->observations = null;

            // Default all active students to present = false
            $this->attendances = [];
            foreach ($class->activeStudents as $student) {
                $this->attendances[$student->id] = false;
            }
        }
    }

    public function toggleAttendance(int $studentId): void
    {
        if ($this->isReadOnly) {
            return;
        }

        $this->attendances[$studentId] = !($this->attendances[$studentId] ?? false);
    }

    public function markAllPresent(): void
    {
        if ($this->isReadOnly) {
            return;
        }

        foreach ($this->attendances as $studentId => $val) {
            $this->attendances[$studentId] = true;
        }
    }

    public function markAllAbsent(): void
    {
        if ($this->isReadOnly) {
            return;
        }

        foreach ($this->attendances as $studentId => $val) {
            $this->attendances[$studentId] = false;
        }
    }

    public function getPresentCountProperty(): int
    {
        return count(array_filter($this->attendances));
    }

    public function getEnrolledCountProperty(): int
    {
        return count($this->attendances);
    }

    public function getAbsentCountProperty(): int
    {
        return max(0, $this->enrolledCount - $this->presentCount);
    }

    public function getTotalCongregationProperty(): int
    {
        $visitors = is_numeric($this->visitorsCount) ? (int) $this->visitorsCount : 0;
        return $this->presentCount + max(0, $visitors);
    }

    public function getAttendanceRateProperty(): int
    {
        if ($this->enrolledCount === 0) {
            return 0;
        }

        return (int) round(($this->presentCount / $this->enrolledCount) * 100);
    }

    public function save(): void
    {
        $this->authorizeAccess();

        if ($this->isReadOnly) {
            throw ValidationException::withMessages([
                'lessonDate' => 'Apenas a secretaria pode alterar chamadas de datas passadas.',
            ]);
        }

        $this->validate([
            'lessonDate' => ['required', 'date'],
            'lessonNumber' => ['nullable', 'string', 'max:50'],
            'lessonTitle' => ['nullable', 'string', 'max:255'],
            'visitorsCount' => ['nullable', 'numeric', 'min:0'],
            'biblesCount' => ['nullable', 'numeric', 'min:0'],
            'magazinesCount' => ['nullable', 'numeric', 'min:0'],
            'offeringsAmount' => ['nullable', 'numeric', 'min:0'],
            'observations' => ['nullable', 'string'],
        ]);

        $user = Auth::user();

        // Transaction wrapping the record and attendances
        DB::transaction(function () use ($user) {
            $isNew = false;
            $record = LessonRecord::where('class_id', $this->classId)
                ->where('lesson_date', $this->lessonDate)
                ->lockForUpdate()
                ->first();

            $beforeData = null;

            if (!$record) {
                $isNew = true;
                $record = new LessonRecord();
                $record->class_id = $this->classId;
                $record->lesson_date = Carbon::parse($this->lessonDate)->format('Y-m-d');
                $record->registered_by = $user->id;
            } else {
                $beforeData = [
                    'visitors_count' => $record->visitors_count,
                    'bibles_count' => $record->bibles_count,
                    'magazines_count' => $record->magazines_count,
                    'offerings_amount' => $record->offerings_amount,
                    'present_count' => $record->attendances()->where('is_present', true)->count(),
                ];
            }

            $record->lesson_number = $this->lessonNumber;
            $record->lesson_title = $this->lessonTitle;
            $record->visitors_count = max(0, (int) ($this->visitorsCount ?: 0));
            $record->bibles_count = max(0, (int) ($this->biblesCount ?: 0));
            $record->magazines_count = max(0, (int) ($this->magazinesCount ?: 0));
            $record->offerings_amount = max(0, (float) ($this->offeringsAmount ?: 0));
            $record->observations = $this->observations;
            $record->save();

            // Save individual attendances
            foreach ($this->attendances as $studentId => $isPresent) {
                LessonAttendance::updateOrCreate(
                    [
                        'lesson_record_id' => $record->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'is_present' => (bool) $isPresent,
                    ]
                );
            }

            $this->existingRecordId = $record->id;

            // Audit logging
            $action = $isNew ? 'ATTENDANCE_RECORDED' : 'ATTENDANCE_RECTIFIED';
            AuditService::log(
                $action,
                LessonRecord::class,
                $record->id,
                $beforeData,
                [
                    'class_id' => $this->classId,
                    'lesson_date' => $this->lessonDate,
                    'present_count' => $this->presentCount,
                    'visitors_count' => $this->visitorsCount,
                    'offerings_amount' => $this->offeringsAmount,
                    'saved_by_role' => $user->role->value,
                ]
            );
        });

        session()->flash('status', 'Chamada salva com sucesso!');
    }

    public function render(): View
    {
        $class = EbdClass::with(['activeStudents'])->findOrFail($this->classId);

        // Fetch students ordered by name
        $students = Student::whereIn('id', array_keys($this->attendances))
            ->orderBy('name')
            ->get();

        return view('livewire.take-attendance', [
            'ebdClass' => $class,
            'students' => $students,
        ]);
    }
}
