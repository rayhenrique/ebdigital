<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class WhatsNewModal extends Component
{
    public bool $showModal = false;

    public string $currentVersion = '';

    public string $selectedVersion = '';

    /**
     * @var array<string, mixed>
     */
    public array $release = [];

    /**
     * @var array<string, array<string, mixed>>
     */
    public array $allReleases = [];

    public function mount(): void
    {
        $this->currentVersion = (string) config('changelog.current_version', '1.0.0');
        $this->allReleases = (array) config('changelog.releases', []);
        $this->selectedVersion = $this->currentVersion;
        $this->release = $this->allReleases[$this->currentVersion] ?? [];

        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            $lastSeen = $user->last_seen_version;

            if ($lastSeen === null || version_compare($lastSeen, $this->currentVersion, '<')) {
                $this->showModal = true;
            }
        }
    }

    #[On('open-whats-new')]
    public function openModal(?string $version = null): void
    {
        $targetVersion = $version && isset($this->allReleases[$version])
            ? $version
            : $this->currentVersion;

        $this->selectedVersion = $targetVersion;
        $this->release = $this->allReleases[$targetVersion] ?? [];
        $this->showModal = true;
    }

    public function selectVersion(string $version): void
    {
        if (isset($this->allReleases[$version])) {
            $this->selectedVersion = $version;
            $this->release = $this->allReleases[$version];
        }
    }

    public function dismiss(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user) {
            $user->update([
                'last_seen_version' => $this->currentVersion,
            ]);
        }

        $this->showModal = false;
    }

    public function closeModalOnly(): void
    {
        $this->showModal = false;
    }

    public function render(): View
    {
        return view('livewire.whats-new-modal');
    }
}
