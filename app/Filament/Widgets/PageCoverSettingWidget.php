<?php

namespace App\Filament\Widgets;

use App\Models\SiteSetting;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Livewire\WithFileUploads;

abstract class PageCoverSettingWidget extends Widget
{
    use WithFileUploads;

    protected string $view = 'filament.widgets.page-cover-setting-widget';

    public $upload = null;

    public ?string $currentPath = null;

    abstract public function settingKey(): string;

    abstract public function heading(): string;

    /**
     * Short label for user-facing copy, e.g. "projects", "news", "services".
     */
    abstract public function listingLabel(): string;

    public function mount(): void
    {
        $this->currentPath = SiteSetting::getValue($this->settingKey());
    }

    public function save(): void
    {
        if ($this->upload) {
            $path = $this->upload->store('pages', 'public');
            SiteSetting::setValue($this->settingKey(), $path);
            $this->currentPath = $path;
            $this->upload = null;
        }

        Notification::make()
            ->title('Saved')
            ->body($this->heading().' image updated: '.($this->currentPath ?? ''))
            ->success()
            ->send();
    }
}
