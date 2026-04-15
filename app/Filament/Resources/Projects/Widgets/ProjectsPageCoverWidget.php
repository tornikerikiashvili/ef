<?php

namespace App\Filament\Resources\Projects\Widgets;

use App\Filament\Widgets\PageCoverSettingWidget;

class ProjectsPageCoverWidget extends PageCoverSettingWidget
{
    public function settingKey(): string
    {
        return 'projects_page_cover';
    }

    public function heading(): string
    {
        return 'Projects page cover';
    }

    public function listingLabel(): string
    {
        return 'projects';
    }
}
