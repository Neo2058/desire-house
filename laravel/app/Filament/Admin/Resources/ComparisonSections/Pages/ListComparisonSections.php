<?php

namespace App\Filament\Admin\Resources\ComparisonSections\Pages;

use App\Filament\Admin\Resources\ComparisonSections\ComparisonSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComparisonSections extends ListRecords
{
    protected static string $resource = ComparisonSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
