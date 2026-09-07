<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Congregation;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TenantService
{
    /**
     * Retorna o ID da congregação ativa no contexto atual.
     * - Para Secretário/Professor: retorna estritamente a sua congregação.
     * - Para Admin: retorna a congregação selecionada na sessão ou null (modo "Todas as Congregações").
     */
    public function getCongregationId(): ?int
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        if ($user->isAdmin()) {
            $selected = session('selected_congregation_id');
            if ($selected === null || $selected === 0 || $selected === 'all') {
                return null;
            }
            return (int) $selected;
        }

        return $user->congregation_id ? (int) $user->congregation_id : 1;
    }

    /**
     * Define a congregação ativa para a sessão do Administrador.
     */
    public function setCongregationId(?int $id): void
    {
        if ($id === null || $id <= 0) {
            session()->forget('selected_congregation_id');
        } else {
            session(['selected_congregation_id' => $id]);
        }
    }

    /**
     * Retorna a model da congregação ativa ou null se estiver em modo global.
     */
    public function getCongregation(): ?Congregation
    {
        $id = $this->getCongregationId();

        if (! $id) {
            return null;
        }

        return Congregation::find($id);
    }

    /**
     * Verifica se o contexto atual é de visualização global (Todas as congregações).
     */
    public function isAllCongregations(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user || ! $user->isAdmin()) {
            return false;
        }

        return $this->getCongregationId() === null;
    }

    /**
     * Retorna as congregações disponíveis para o usuário autenticado.
     *
     * @return Collection<int, Congregation>
     */
    public function getAvailableCongregations(): Collection
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return new Collection();
        }

        if ($user->isAdmin()) {
            return Congregation::active()->orderBy('is_headquarters', 'desc')->orderBy('name')->get();
        }

        return Congregation::where('id', $user->congregation_id ?? 1)->get();
    }
}
