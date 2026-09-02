<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Имя')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('password')
                    ->label('Пароль')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->confirmed()
                    ->minLength(8)
                    ->helperText(fn (string $operation): ?string => $operation === 'edit'
                        ? 'Оставьте пустым, если не нужно менять пароль.'
                        : null),

                TextInput::make('password_confirmation')
                    ->label('Подтверждение пароля')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(false),

                Select::make('roles')
                    ->label('Роли')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->helperText('Для входа в админку нужна роль super_admin или panel_user.'),

                Toggle::make('must_change_password')
                    ->label('Сменить пароль при следующем входе')
                    ->default(false),
            ]);
    }
}
