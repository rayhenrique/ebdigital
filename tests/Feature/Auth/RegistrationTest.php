<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Models\Congregation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $congregation = Congregation::create([
            'name' => 'Congregação Monte Sião',
            'slug' => 'monte-siao',
            'city' => 'Teotônio Vilela',
            'is_active' => true,
        ]);

        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Solicitar Cadastro');
        $response->assertSee('Monte Sião');
        $response->assertSee('Secretário / Superintendente');
        $response->assertSee('Professor');
    }

    public function test_login_page_contains_self_registration_link(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee(route('register'));
        $response->assertSee('Solicitar Cadastro');
    }

    public function test_new_users_can_register_and_require_admin_approval(): void
    {
        $congregation = Congregation::create([
            'name' => 'Congregação Belém',
            'slug' => 'belem',
            'city' => 'Teotônio Vilela',
            'is_active' => true,
        ]);

        $response = $this->post('/register', [
            'name' => 'Professor João Batista',
            'email' => 'joao.batista@ebd.local',
            'password' => 'senhaSegura123',
            'password_confirmation' => 'senhaSegura123',
            'role' => UserRole::PROFESSOR->value,
            'congregation_id' => $congregation->id,
        ]);

        // Não deve efetuar login automático; deve permanecer visitante (guest)
        $this->assertGuest();
        $response->assertRedirect(route('register.success'));

        // Usuário deve ter sido persistido no banco com is_active = false
        $this->assertDatabaseHas('users', [
            'name' => 'Professor João Batista',
            'email' => 'joao.batista@ebd.local',
            'role' => UserRole::PROFESSOR->value,
            'congregation_id' => $congregation->id,
            'is_active' => false,
        ]);
    }

    public function test_register_success_screen_renders_with_whatsapp_link_and_data(): void
    {
        $congregation = Congregation::create([
            'name' => 'Templo Central',
            'slug' => 'templo-central',
            'city' => 'Teotônio Vilela',
            'is_active' => true,
        ]);

        $postResponse = $this->post('/register', [
            'name' => 'Secretário Marcos',
            'email' => 'marcos@ebd.local',
            'password' => 'senhaSegura123',
            'password_confirmation' => 'senhaSegura123',
            'role' => UserRole::SECRETARIO->value,
            'congregation_id' => $congregation->id,
        ]);

        $postResponse->assertRedirect(route('register.success'));

        $successResponse = $this->get(route('register.success'));
        $successResponse->assertStatus(200);
        $successResponse->assertSee('Cadastro Realizado!');
        $successResponse->assertSee('Secretário Marcos');
        $successResponse->assertSee('marcos@ebd.local');
        $successResponse->assertSee('+55 (82) 99630-4742');
        $successResponse->assertSee('https://wa.me/5582996304742', false);
        $successResponse->assertSee('Liberar pelo WhatsApp');
    }

    public function test_user_cannot_self_register_as_admin(): void
    {
        $congregation = Congregation::create([
            'name' => 'Congregação Filadélfia',
            'slug' => 'filadelfia',
            'city' => 'Teotônio Vilela',
            'is_active' => true,
        ]);

        $response = $this->post('/register', [
            'name' => 'Tentativa Admin',
            'email' => 'admin.fake@ebd.local',
            'password' => 'senhaSegura123',
            'password_confirmation' => 'senhaSegura123',
            'role' => UserRole::ADMIN->value,
            'congregation_id' => $congregation->id,
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', [
            'email' => 'admin.fake@ebd.local',
        ]);
    }

    public function test_registration_requires_active_congregation(): void
    {
        $inactiveCongregation = Congregation::create([
            'name' => 'Congregação Desativada',
            'slug' => 'desativada',
            'city' => 'Teotônio Vilela',
            'is_active' => false,
        ]);

        $response = $this->post('/register', [
            'name' => 'Professor Inativo',
            'email' => 'inativo@ebd.local',
            'password' => 'senhaSegura123',
            'password_confirmation' => 'senhaSegura123',
            'role' => UserRole::PROFESSOR->value,
            'congregation_id' => $inactiveCongregation->id,
        ]);

        $response->assertSessionHasErrors('congregation_id');
        $this->assertDatabaseMissing('users', [
            'email' => 'inativo@ebd.local',
        ]);
    }

    public function test_unapproved_user_cannot_login_until_approved(): void
    {
        $congregation = Congregation::create([
            'name' => 'Congregação Ebenézer',
            'slug' => 'ebenezer',
            'city' => 'Teotônio Vilela',
            'is_active' => true,
        ]);

        $this->post('/register', [
            'name' => 'Prof. Lucas',
            'email' => 'lucas@ebd.local',
            'password' => 'senhaSegura123',
            'password_confirmation' => 'senhaSegura123',
            'role' => UserRole::PROFESSOR->value,
            'congregation_id' => $congregation->id,
        ]);

        // Tentativa de login com a conta ainda inativa
        $loginResponse = $this->post('/login', [
            'email' => 'lucas@ebd.local',
            'password' => 'senhaSegura123',
        ]);

        $this->assertGuest();
        $loginResponse->assertSessionHasErrors('email');
    }

    public function test_admin_approval_activates_user_and_allows_login(): void
    {
        $congregation = Congregation::create([
            'name' => 'Congregação Betel',
            'slug' => 'betel',
            'city' => 'Teotônio Vilela',
            'is_active' => true,
        ]);

        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $this->post('/register', [
            'name' => 'Professora Sara',
            'email' => 'sara@ebd.local',
            'password' => 'senhaSegura123',
            'password_confirmation' => 'senhaSegura123',
            'role' => UserRole::PROFESSOR->value,
            'congregation_id' => $congregation->id,
        ]);

        $registeredUser = User::where('email', 'sara@ebd.local')->firstOrFail();
        $this->assertFalse($registeredUser->is_active);

        // Admin acessa a tela de usuários e vê o usuário pendente
        $adminUsersResponse = $this->actingAs($admin)->get('/admin/users');
        $adminUsersResponse->assertStatus(200);
        $adminUsersResponse->assertSee('sara@ebd.local');
        $adminUsersResponse->assertSee('Aguardando Aprovação');

        // Admin aprova o usuário
        $toggleResponse = $this->actingAs($admin)->patch("/admin/users/{$registeredUser->id}/toggle");
        $toggleResponse->assertSessionHas('success');

        $registeredUser->refresh();
        $this->assertTrue($registeredUser->is_active);

        // Usuário agora faz login com sucesso
        $this->post('/logout');

        $loginResponse = $this->post('/login', [
            'email' => 'sara@ebd.local',
            'password' => 'senhaSegura123',
        ]);

        $this->assertAuthenticated();
        $loginResponse->assertRedirect(route('dashboard', absolute: false));
    }
}
