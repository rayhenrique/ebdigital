<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Log an auditable action to audit_logs table.
     *
     * @param string $action
     * @param string $auditableType
     * @param int|string $auditableId
     * @param array<string, mixed>|null $payloadBefore
     * @param array<string, mixed>|null $payloadAfter
     * @param int|null $userId
     * @return AuditLog
     */
    public static function log(
        string $action,
        string $auditableType,
        int|string $auditableId,
        ?array $payloadBefore = null,
        ?array $payloadAfter = null,
        ?int $userId = null
    ): AuditLog {
        $tenantService = app(TenantService::class);
        $congregationId = $tenantService->getCongregationId() ?? Auth::user()?->congregation_id ?? 1;

        return AuditLog::create([
            'congregation_id' => $congregationId,
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'auditable_type' => $auditableType,
            'auditable_id' => (int) $auditableId,
            'payload_before' => $payloadBefore,
            'payload_after' => $payloadAfter,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
