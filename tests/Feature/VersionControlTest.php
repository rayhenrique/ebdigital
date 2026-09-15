<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Livewire\WhatsNewModal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VersionControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_no_seen_version_sees_modal_on_mount(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
            'last_seen_version' => null,
        ]);

        $this->actingAs($user);

        Livewire::test(WhatsNewModal::class)
            ->assertSet('showModal', true)
            ->assertSee('O Que Há de Novo?')
            ->assertSee('v1.6.0');
    }

    public function test_user_with_current_version_already_seen_does_not_see_modal(): void
    {
        $currentVersion = config('changelog.current_version', '1.6.0');

        $user = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
            'last_seen_version' => $currentVersion,
        ]);

        $this->actingAs($user);

        Livewire::test(WhatsNewModal::class)
            ->assertSet('showModal', false)
            ->assertDontSee('O Que Há de Novo?');
    }

    public function test_user_with_older_version_sees_modal(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::SECRETARIO,
            'is_active' => true,
            'last_seen_version' => '1.5.0',
        ]);

        $this->actingAs($user);

        Livewire::test(WhatsNewModal::class)
            ->assertSet('showModal', true)
            ->assertSee('O Que Há de Novo?')
            ->assertSee('v1.6.0');
    }

    public function test_user_can_dismiss_modal_and_updates_last_seen_version(): void
    {
        $currentVersion = (string) config('changelog.current_version', '1.6.0');

        $user = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
            'last_seen_version' => null,
        ]);

        $this->actingAs($user);

        Livewire::test(WhatsNewModal::class)
            ->assertSet('showModal', true)
            ->call('dismiss')
            ->assertSet('showModal', false);

        $this->assertSame($currentVersion, $user->fresh()->last_seen_version);
    }

    public function test_modal_can_be_opened_via_event_and_switch_versions(): void
    {
        $currentVersion = config('changelog.current_version', '1.6.0');

        $user = User::factory()->create([
            'role' => UserRole::ADMIN,
            'is_active' => true,
            'last_seen_version' => $currentVersion,
        ]);

        $this->actingAs($user);

        Livewire::test(WhatsNewModal::class)
            ->assertSet('showModal', false)
            ->dispatch('open-whats-new')
            ->assertSet('showModal', true)
            ->call('selectVersion', '1.5.0')
            ->assertSet('selectedVersion', '1.5.0')
            ->assertSee('Manual Didático Interativo');
    }

    public function test_authenticated_dashboard_page_renders_modal_for_first_login(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::PROFESSOR,
            'is_active' => true,
            'last_seen_version' => null,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('O Que Há de Novo?');
        $response->assertSee('Novidades');
    }
}
