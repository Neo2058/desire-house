<?php

namespace App\Filament\Admin\Resources\ComparisonSections\Pages;

use App\Filament\Admin\Resources\ComparisonSections\ComparisonSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComparisonSection extends EditRecord
{
    protected static string $resource = ComparisonSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
