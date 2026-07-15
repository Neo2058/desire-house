<?php

namespace App\Filament\Admin\Resources\Leads\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable(),


                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),


                TextColumn::make('object_type')
                    ->label('Объект'),


                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {

                        'new' => 'danger',

                        'work' => 'warning',

                        'clarification' => 'info',

                        'payment' => 'success',

                        'done' => 'success',

                        default => 'gray',

                    }),

                IconColumn::make('processed')
                    ->label('Обработано')
                    ->boolean(),


                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
