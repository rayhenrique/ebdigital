<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AuditPruneTest extends TestCase
{
    use RefreshDatabase;

    public function test_artisan_audit_prune_removes_logs_older_than_90_days(): void
    {
        $admin = User::factory()->admin()->create();

        // 1. Old log (95 days old)
        $oldLog = AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'OLD_ACTION',
            'auditable_type' => User::class,
            'auditable_id' => $admin->id,
            'created_at' => Carbon::now()->subDays(95),
        ]);

        // 2. Recent log (20 days old)
        $recentLog = AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'RECENT_ACTION',
            'auditable_type' => User::class,
            'auditable_id' => $admin->id,
            'created_at' => Carbon::now()->subDays(20),
        ]);

        $this->artisan('audit:prune', ['--days' => 90])
            ->assertSuccessful();

        $this->assertDatabaseMissing('audit_logs', ['id' => $oldLog->id]);
        $this->assertDatabaseHas('audit_logs', ['id' => $recentLog->id]);
    }

    public function test_admin_manual_purge_removes_logs_older_than_30_days(): void
    {
        $admin = User::factory()->admin()->create();

        // Old log (40 days old)
        $oldLog = AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'PAST_ACTION',
            'auditable_type' => User::class,
            'auditable_id' => $admin->id,
            'created_at' => Carbon::now()->subDays(40),
        ]);

        // Recent log (10 days old)
        $recentLog = AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'NEW_ACTION',
            'auditable_type' => User::class,
            'auditable_id' => $admin->id,
            'created_at' => Carbon::now()->subDays(10),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.audit.purge'));
        $response->assertRedirect(route('admin.audit.index'));

        $this->assertDatabaseMissing('audit_logs', ['id' => $oldLog->id]);
        $this->assertDatabaseHas('audit_logs', ['id' => $recentLog->id]);

        // Check that manual purge was recorded in audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'AUDIT_MANUAL_PURGE',
        ]);
    }
}
