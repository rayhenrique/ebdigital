<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_reset_password_of_any_user(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $teacher = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
            'password' => Hash::make('old_password_123'),
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.users.reset-password', $teacher), [
                'new_password' => 'novasenha2026',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Confirma que a nova senha funciona
        $this->assertTrue(Hash::check('novasenha2026', $teacher->fresh()->password));

        // Confirma que a ação gerou log de auditoria
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'USER_PASSWORD_RESET',
            'auditable_type' => User::class,
            'auditable_id' => $teacher->id,
            'user_id' => $admin->id,
        ]);
    }

    public function test_non_admin_cannot_reset_password_of_other_users(): void
    {
        $secretary = User::factory()->create([
            'role' => UserRole::SECRETARIO,
            'is_active' => true,
        ]);

        $teacher = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
            'password' => Hash::make('segredo123'),
        ]);

        $response = $this->actingAs($secretary)
            ->patch(route('admin.users.reset-password', $teacher), [
                'new_password' => 'tentativainvalida',
            ]);

        $response->assertForbidden();

        // Senha permanece inalterada
        $this->assertTrue(Hash::check('segredo123', $teacher->fresh()->password));
    }

    public function test_password_reset_requires_minimum_six_characters(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $teacher = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
            'password' => Hash::make('segredo123'),
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.users.reset-password', $teacher), [
                'new_password' => '12345',
            ]);

        $response->assertSessionHasErrors('new_password');
        $this->assertTrue(Hash::check('segredo123', $teacher->fresh()->password));
    }
}
