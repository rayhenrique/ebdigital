<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_admin_is_redirected_to_dashboard_and_can_access_it(): void
    {
        $admin = User::factory()->create([
            'role' => \App\Enums\UserRole::ADMIN,
            'congregation_id' => null,
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));

        $dashboardResponse = $this->actingAs($admin)->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSeeLivewire(\App\Livewire\DailyConsolidatedDashboard::class);
    }

    public function test_all_user_roles_can_access_dashboard_with_or_without_congregation(): void
    {
        $congregation = \App\Models\Congregation::firstOrCreate(
            ['slug' => 'sede-test'],
            ['name' => 'Templo Sede', 'is_headquarters' => true, 'is_active' => true]
        );

        foreach ([\App\Enums\UserRole::ADMIN, \App\Enums\UserRole::SECRETARIO, \App\Enums\UserRole::PROFESSOR] as $role) {
            // Com congregação
            $userWithCongregation = User::factory()->create([
                'role' => $role,
                'congregation_id' => $congregation->id,
            ]);
            $this->actingAs($userWithCongregation)->get('/dashboard')->assertStatus(200);

            // Sem congregação vinculada
            $userWithoutCongregation = User::factory()->create([
                'role' => $role,
                'congregation_id' => null,
            ]);
            $this->actingAs($userWithoutCongregation)->get('/dashboard')->assertStatus(200);
        }
    }

    public function test_secretaria_dashboard_route_redirects_to_central_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/secretaria/dashboard');
        $response->assertRedirect(route('dashboard'));
    }
}
