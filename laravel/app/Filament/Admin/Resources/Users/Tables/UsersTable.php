<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles.name')
                    ->label('Роли')
                    ->badge()
                    ->separator(','),

                IconColumn::make('must_change_password')
                    ->label('Смена пароля')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->disabled(fn (User $record): bool => $record->is(auth()->user())),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
