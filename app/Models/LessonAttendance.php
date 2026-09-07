<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $lesson_record_id
 * @property int $student_id
 * @property bool $is_present
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read LessonRecord $lessonRecord
 * @property-read Student $student
 */
class LessonAttendance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lesson_attendances';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'lesson_record_id',
        'student_id',
        'is_present',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_present' => 'boolean',
        ];
    }

    /**
     * The lesson record this attendance belongs to.
     */
    public function lessonRecord(): BelongsTo
    {
        return $this->belongsTo(LessonRecord::class, 'lesson_record_id');
    }

    /**
     * The student this attendance belongs to.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
