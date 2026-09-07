<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $class_id
 * @property string $name
 * @property string|null $phone
 * @property Carbon|null $birth_date
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read EbdClass $ebdClass
 * @property-read \Illuminate\Database\Eloquent\Collection<int, LessonAttendance> $attendances
 */
class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'class_id',
        'name',
        'phone',
        'birth_date',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * The class this student belongs to.
     */
    public function ebdClass(): BelongsTo
    {
        return $this->belongsTo(EbdClass::class, 'class_id');
    }

    /**
     * Attendances for this student.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(LessonAttendance::class, 'student_id');
    }

    /**
     * Scope a query to only include active students.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
