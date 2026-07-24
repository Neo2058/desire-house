<?php

namespace App\Filament\Admin\Resources\SiteSettings\Pages;

use App\Filament\Admin\Resources\SiteSettings\SiteSettingResource;
use Filament\Resources\Pages\ListRecords;
use App\Models\SiteSetting;
use Filament\Actions;

class ListSiteSettings extends ListRecords
{
    protected static string $resource = SiteSettingResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [];

        if (SiteSetting::count() === 0) {
            $actions[] = Actions\CreateAction::make();
        }

        return $actions;
    }
}
