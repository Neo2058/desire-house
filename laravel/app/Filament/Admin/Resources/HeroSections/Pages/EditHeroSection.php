<?php

namespace App\Filament\Admin\Resources\HeroSections\Pages;

use App\Filament\Admin\Resources\HeroSections\HeroSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHeroSection extends EditRecord
{
    protected static string $resource = HeroSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
