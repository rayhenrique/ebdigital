<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? $this->route('user');

        return [
            'congregation_id' => ['nullable', 'integer', 'exists:congregations,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:191', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', Password::defaults()],
            'role' => ['required', Rule::enum(UserRole::class)],
            'is_active' => ['boolean'],
        ];
    }
}
