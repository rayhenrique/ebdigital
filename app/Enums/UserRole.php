<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case SECRETARIO = 'secretario';
    case PROFESSOR = 'professor';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::SECRETARIO => 'Secretário / Superintendente',
            self::PROFESSOR => 'Professor',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isSecretario(): bool
    {
        return $this === self::SECRETARIO;
    }

    public function isProfessor(): bool
    {
        return $this === self::PROFESSOR;
    }
}
