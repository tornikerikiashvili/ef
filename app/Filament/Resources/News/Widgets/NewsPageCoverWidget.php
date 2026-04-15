<?php

namespace App\Filament\Resources\News\Widgets;

use App\Filament\Widgets\PageCoverSettingWidget;

class NewsPageCoverWidget extends PageCoverSettingWidget
{
    public function settingKey(): string
    {
        return 'news_page_cover';
    }

    public function heading(): string
    {
        return 'News page cover';
    }

    public function listingLabel(): string
    {
        return 'news';
    }
}
