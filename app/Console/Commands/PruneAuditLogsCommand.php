<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class PruneAuditLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:prune {--days=90 : Number of days to retain logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expunge audit logs older than the specified retention days (default 90 days)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');

        if ($days < 1) {
            $this->error('A quantidade de dias deve ser maior que zero.');
            return self::FAILURE;
        }

        $cutoffDate = now()->subDays($days);

        $deletedCount = AuditLog::where('created_at', '<', $cutoffDate)->delete();

        $this->info("Excluídos {$deletedCount} registros de auditoria anteriores a {$cutoffDate->format('d/m/Y H:i:s')} ({$days} dias).");

        return self::SUCCESS;
    }
}
