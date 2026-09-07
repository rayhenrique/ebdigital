<?php

declare(strict_types=1);

namespace App\Http\Requests\Secretaria;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User|null $user */
        $user = $this->user();
        if (! $user || (! $user->isAdmin() && ! $user->isSecretario())) {
            return false;
        }

        $targetUser = $this->route('professor') ?? $this->route('professore') ?? $this->route('teacher');
        if ($targetUser instanceof User && $user->isSecretario()) {
            return ($user->congregation_id ?? 1) === ($targetUser->congregation_id ?? 1);
        }

        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var User $targetUser */
        $targetUser = $this->route('professor') ?? $this->route('professore') ?? $this->route('teacher');
        $userId = $targetUser instanceof User ? $targetUser->id : (int) $targetUser;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:25'],
            'is_active' => ['nullable', 'boolean'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nome do Professor',
            'email' => 'E-mail',
            'password' => 'Senha de Acesso',
            'phone' => 'Telefone / WhatsApp',
            'is_active' => 'Status Ativo',
            'class_ids' => 'Turmas Atribuídas',
        ];
    }
}
