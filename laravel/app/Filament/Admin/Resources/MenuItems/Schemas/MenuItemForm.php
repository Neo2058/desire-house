<?php

namespace App\Filament\Admin\Resources\MenuItems\Schemas;

use Filament\Schemas\Schema;
use App\Models\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Название')
                    ->required(),

                Select::make('page_id')
                    ->label('Страница')
                    ->relationship('page', 'title')
                    ->searchable()
                    ->preload()
                    ->nullable(),

                TextInput::make('url')
                    ->label('Внешняя ссылка')
                    ->nullable(),

                TextInput::make('sort')
                    ->label('Порядок')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true),
            ]);
    }
}
