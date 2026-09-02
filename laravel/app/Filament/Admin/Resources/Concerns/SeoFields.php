<?php

namespace App\Filament\Admin\Resources\Concerns;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

final class SeoFields
{
    public static function make(): Section
    {
        return Section::make('SEO')
            ->description('Если поля пустые, подставятся название и описание записи.')
            ->relationship('seo')
            ->schema([
                TextInput::make('title')
                    ->label('Meta Title')
                    ->maxLength(70),

                Textarea::make('description')
                    ->label('Meta Description')
                    ->rows(3)
                    ->maxLength(180),
            ])
            ->collapsed()
            ->columnSpanFull();
    }
}
