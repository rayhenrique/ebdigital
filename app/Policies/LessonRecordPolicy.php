<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\EbdClass;
use App\Models\LessonRecord;
use App\Models\User;
use Carbon\Carbon;

class LessonRecordPolicy
{
    /**
     * Determine whether the user can view any lesson records.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_active;
    }

    /**
     * Determine whether the user can view the lesson record.
     */
    public function view(User $user, LessonRecord $lessonRecord): bool
    {
        if (!$user->is_active) {
            return false;
        }

        if ($user->isAdmin() || $user->isSecretario()) {
            return true;
        }

        // Professor must be assigned to the class
        return $user->teachingClasses()->where('classes.id', $lessonRecord->class_id)->exists();
    }

    /**
     * Determine whether the user can create lesson records for a class on a given date.
     */
    public function create(User $user, ?EbdClass $class = null, ?string $lessonDate = null): bool
    {
        if (!$user->is_active) {
            return false;
        }

        if ($user->isAdmin() || $user->isSecretario()) {
            return true;
        }

        if ($user->isProfessor()) {
            if ($class && !$user->teachingClasses()->where('classes.id', $class->id)->exists()) {
                return false;
            }

            if ($lessonDate && !Carbon::parse($lessonDate)->isToday()) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the lesson record.
     */
    public function update(User $user, LessonRecord $lessonRecord): bool
    {
        if (!$user->is_active) {
            return false;
        }

        // Admin and Secretario can update any lesson record retroactively
        if ($user->isAdmin() || $user->isSecretario()) {
            return true;
        }

        // Professor can only update if:
        // 1. Teaches this class
        // 2. The lesson record is for TODAY
        if ($user->isProfessor()) {
            $isAssigned = $user->teachingClasses()->where('classes.id', $lessonRecord->class_id)->exists();
            $isToday = Carbon::parse($lessonRecord->lesson_date)->isToday();

            return $isAssigned && $isToday;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the lesson record.
     */
    public function delete(User $user, LessonRecord $lessonRecord): bool
    {
        return $user->is_active && ($user->isAdmin() || $user->isSecretario());
    }
}
