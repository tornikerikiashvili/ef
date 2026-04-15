<?php

namespace App\Filament\Resources\Services\Widgets;

use App\Filament\Widgets\PageCoverSettingWidget;

class ServicesPageCoverWidget extends PageCoverSettingWidget
{
    public function settingKey(): string
    {
        return 'services_page_cover';
    }

    public function heading(): string
    {
        return 'Services page cover';
    }

    public function listingLabel(): string
    {
        return 'services';
    }
}
