<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManualTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_manual(): void
    {
        $response = $this->get(route('manual.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_manual(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('manual.index'));
        $response->assertOk();
        $response->assertSee('Manual de Uso Didático da EBD Digital');
        $response->assertSee('Módulo do Pastor e Administrador Geral');
    }

    public function test_secretario_can_access_manual(): void
    {
        $secretario = User::factory()->create([
            'role' => UserRole::SECRETARIO,
            'is_active' => true,
        ]);

        $response = $this->actingAs($secretario)->get(route('manual.index'));
        $response->assertOk();
        $response->assertSee('Manual de Uso Didático da EBD Digital');
        $response->assertSee('Módulo da Secretaria e Superintendência');
    }

    public function test_professor_can_access_manual(): void
    {
        $professor = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $response = $this->actingAs($professor)->get(route('manual.index'));
        $response->assertOk();
        $response->assertSee('Manual de Uso Didático da EBD Digital');
        $response->assertSee('Módulo do Professor: O Dia a Dia na Sala de Aula');
    }

    public function test_manual_contains_all_core_module_sections_and_faq(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('manual.index'));
        $response->assertOk();
        $response->assertSee('Matrícula Rápida');
        $response->assertSee('Modal de Confirmação');
        $response->assertSee('Tenant Switcher');
        $response->assertSee('Como Instalar no Android');
        $response->assertSee('Como Instalar no iPhone');
        $response->assertSee('Perguntas Frequentes');
    }
}
