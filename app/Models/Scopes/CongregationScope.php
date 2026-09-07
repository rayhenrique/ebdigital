<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Services\TenantService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CongregationScope implements Scope
{
    /**
     * Aplica o escopo de congregação na consulta do Eloquent.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! Auth::check()) {
            return;
        }

        $tenantService = app(TenantService::class);
        $congregationId = $tenantService->getCongregationId();

        if ($congregationId !== null) {
            $builder->where($model->qualifyColumn('congregation_id'), $congregationId);
        }
    }
}
