<?php

namespace App\Filament\Admin\Resources\Images\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ImagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image')
                    ->label(''),

                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('slug')
                    ->searchable(),

            ]);
    }
}
