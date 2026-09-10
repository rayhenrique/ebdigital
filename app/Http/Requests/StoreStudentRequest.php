<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
            return false;
        }

        if ($user->isAdmin() || $user->isSecretario()) {
            return true;
        }

        if ($user->isProfessor()) {
            $classId = $this->input('class_id');
            if (! $classId) {
                return true;
            }

            return $user->teachingClasses()->where('classes.id', (int) $classId)->exists();
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'is_active' => ['boolean'],
        ];
    }
}
