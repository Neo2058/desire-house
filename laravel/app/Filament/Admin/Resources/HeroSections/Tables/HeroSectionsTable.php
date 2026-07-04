<?php

namespace App\Filament\Admin\Resources\HeroSections\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class HeroSectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Название')
                    ->searchable(),

                TextColumn::make('title')
                    ->label('Заголовок')
                    ->searchable(),

                TextColumn::make('updated_at')
                    ->label('Изменён')
                    ->dateTime('d.m.Y H:i'),

            ]);
    }
}
