<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $class_id
 * @property int $registered_by
 * @property Carbon|string $lesson_date
 * @property string|null $lesson_number
 * @property string|null $lesson_title
 * @property int $visitors_count
 * @property int $bibles_count
 * @property int $magazines_count
 * @property string|float $offerings_amount
 * @property string|null $observations
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read int $present_students_count
 * @property-read int $total_students_count
 * @property-read int $total_attendance
 * @property-read EbdClass $ebdClass
 * @property-read User $registeredBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, LessonAttendance> $attendances
 */
class LessonRecord extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lesson_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'class_id',
        'registered_by',
        'lesson_date',
        'lesson_number',
        'lesson_title',
        'visitors_count',
        'bibles_count',
        'magazines_count',
        'offerings_amount',
        'observations',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lesson_date' => 'date:Y-m-d',
            'visitors_count' => 'integer',
            'bibles_count' => 'integer',
            'magazines_count' => 'integer',
            'offerings_amount' => 'decimal:2',
        ];
    }

    /**
     * The class this lesson belongs to.
     */
    public function ebdClass(): BelongsTo
    {
        return $this->belongsTo(EbdClass::class, 'class_id');
    }

    /**
     * The user who recorded this lesson.
     */
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    /**
     * The student attendances for this lesson.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(LessonAttendance::class, 'lesson_record_id');
    }

    /**
     * Total present students count.
     */
    public function getPresentStudentsCountAttribute(): int
    {
        return (int) $this->attendances()->where('is_present', true)->count();
    }

    /**
     * Total enrolled students count in this record.
     */
    public function getTotalStudentsCountAttribute(): int
    {
        return (int) $this->attendances()->count();
    }

    /**
     * Total congregation presence (present enrolled students + visitors).
     */
    public function getTotalAttendanceAttribute(): int
    {
        return $this->present_students_count + (int) $this->visitors_count;
    }
}
