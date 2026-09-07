<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\BelongsToCongregation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $congregation_id
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $students_count
 * @property-read int|null $teachers_count
 * @property-read int|null $active_students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $teachers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Student> $students
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Student> $activeStudents
 * @property-read \Illuminate\Database\Eloquent\Collection<int, LessonRecord> $lessonRecords
 */
class EbdClass extends Model
{
    use HasFactory, BelongsToCongregation;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'congregation_id',
        'name',
        'description',
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
            'is_active' => 'boolean',
        ];
    }

    /**
     * Teachers assigned to this class.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_teacher', 'class_id', 'user_id')
            ->withPivot('created_at');
    }

    /**
     * Students belonging to this class.
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    /**
     * Active students belonging to this class ordered by name.
     */
    public function activeStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id')
            ->where('is_active', true)
            ->orderBy('name');
    }

    /**
     * Lesson records of this class.
     */
    public function lessonRecords(): HasMany
    {
        return $this->hasMany(LessonRecord::class, 'class_id');
    }

    /**
     * Scope a query to only include active classes.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
