<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $pastor_dirigente
 * @property string|null $phone
 * @property string|null $address
 * @property string $city
 * @property bool $is_headquarters
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read \Illuminate\Database\Eloquent\Collection<int, EbdClass> $classes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Student> $students
 * @property-read \Illuminate\Database\Eloquent\Collection<int, LessonRecord> $lessonRecords
 */
class Congregation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'congregations';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'pastor_dirigente',
        'phone',
        'address',
        'city',
        'is_headquarters',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_headquarters' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Congregation $congregation) {
            if (empty($congregation->slug)) {
                $congregation->slug = Str::slug($congregation->name);
            }
        });
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'congregation_id');
    }

    public function secretarios(): HasMany
    {
        return $this->hasMany(User::class, 'congregation_id')->where('role', UserRole::SECRETARIO);
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(User::class, 'congregation_id')->where('role', UserRole::PROFESSOR);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(EbdClass::class, 'congregation_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'congregation_id');
    }

    public function lessonRecords(): HasMany
    {
        return $this->hasMany(LessonRecord::class, 'congregation_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeHeadquarters(Builder $query): Builder
    {
        return $query->where('is_headquarters', true);
    }
}
