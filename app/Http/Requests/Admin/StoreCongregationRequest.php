<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCongregationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'pastor_dirigente' => ['nullable', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'is_headquarters' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nome da Congregação',
            'pastor_dirigente' => 'Pastor / Dirigente',
            'phone' => 'Telefone / WhatsApp',
            'address' => 'Endereço',
            'city' => 'Cidade',
            'is_headquarters' => 'Templo Sede',
            'is_active' => 'Status Ativo',
        ];
    }
}
