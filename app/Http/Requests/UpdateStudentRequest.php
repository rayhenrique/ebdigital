<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
            /** @var \App\Models\Student|null $aluno */
            $aluno = $this->route('aluno');
            if (! $aluno) {
                return false;
            }

            $teachesCurrent = $user->teachingClasses()->where('classes.id', $aluno->class_id)->exists();
            if (! $teachesCurrent) {
                return false;
            }

            $newClassId = $this->input('class_id');
            if ($newClassId && (int) $newClassId !== (int) $aluno->class_id) {
                return $user->teachingClasses()->where('classes.id', (int) $newClassId)->exists();
            }

            return true;
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
