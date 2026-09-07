<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Congregation;
use App\Models\Scopes\CongregationScope;
use App\Services\TenantService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToCongregation
{
    /**
     * Inicializa o trait para registrar o escopo global e auto-atribuir congregação no insert.
     */
    protected static function bootBelongsToCongregation(): void
    {
        static::addGlobalScope(new CongregationScope());

        static::creating(function ($model) {
            if (empty($model->congregation_id)) {
                $tenantService = app(TenantService::class);
                $congregationId = $tenantService->getCongregationId();

                if ($congregationId !== null) {
                    $model->congregation_id = $congregationId;
                } elseif (Auth::check() && Auth::user()->congregation_id) {
                    $model->congregation_id = Auth::user()->congregation_id;
                } else {
                    $model->congregation_id = 1;
                }
            }
        });
    }

    /**
     * Relação com a congregação dona do registro.
     */
    public function congregation(): BelongsTo
    {
        return $this->belongsTo(Congregation::class, 'congregation_id');
    }
}
