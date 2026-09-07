<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = AuditLog::with('user')->orderByDesc('created_at');

        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->input('action')}%");
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('admin.audit.index', [
            'logs' => $logs,
        ]);
    }

    /**
     * Manual purge of logs older than 30 days (RF15).
     */
    public function purge(): RedirectResponse
    {
        $cutoffDate = now()->subDays(30);

        $deleted = AuditLog::where('created_at', '<', $cutoffDate)->delete();

        AuditService::log(
            'AUDIT_MANUAL_PURGE',
            AuditLog::class,
            0,
            null,
            [
                'deleted_records_count' => $deleted,
                'cutoff_date' => $cutoffDate->toDateTimeString(),
                'retained_days' => 30,
            ]
        );

        return redirect()->route('admin.audit.index')->with(
            'success',
            "Limpeza concluída! Foram expurgados {$deleted} registros com mais de 30 dias."
        );
    }
}
