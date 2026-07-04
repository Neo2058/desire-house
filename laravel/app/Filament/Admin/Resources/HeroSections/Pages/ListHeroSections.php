<?php

namespace App\Filament\Admin\Resources\HeroSections\Pages;

use App\Filament\Admin\Resources\HeroSections\HeroSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHeroSections extends ListRecords
{
    protected static string $resource = HeroSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
