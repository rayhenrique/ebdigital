<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\EbdClass;
use App\Models\Student;
use App\Models\User;

class StudentPolicy
{
    /**
     * Determine whether the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        return (bool) $user->is_active;
    }

    /**
     * Determine whether the user can view the student.
     */
    public function view(User $user, Student $student): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->isAdmin() || $user->isSecretario()) {
            return true;
        }

        if ($user->isProfessor()) {
            return $user->teachingClasses()->where('classes.id', $student->class_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create students.
     */
    public function create(User $user, ?EbdClass $class = null): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->isAdmin() || $user->isSecretario()) {
            return true;
        }

        if ($user->isProfessor()) {
            if ($class) {
                return $user->teachingClasses()->where('classes.id', $class->id)->exists();
            }

            return $user->teachingClasses()->where('classes.is_active', true)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can update the student.
     */
    public function update(User $user, Student $student, ?EbdClass $newClass = null): bool
    {
        if (! $user->is_active) {
            return false;
        }

        if ($user->isAdmin() || $user->isSecretario()) {
            return true;
        }

        if ($user->isProfessor()) {
            $teachesCurrent = $user->teachingClasses()->where('classes.id', $student->class_id)->exists();
            if (! $teachesCurrent) {
                return false;
            }

            if ($newClass && $newClass->id !== $student->class_id) {
                return $user->teachingClasses()->where('classes.id', $newClass->id)->exists();
            }

            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can toggle the active status of the student.
     */
    public function toggle(User $user, Student $student): bool
    {
        return $this->update($user, $student);
    }

    /**
     * Determine whether the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
    {
        return (bool) $user->is_active && ($user->isAdmin() || $user->isSecretario());
    }
}
