<?php

declare(strict_types=1);

namespace App\Http\Requests\Secretaria;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User|null $user */
        $user = $this->user();
        return $user && ($user->isAdmin() || $user->isSecretario());
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
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
