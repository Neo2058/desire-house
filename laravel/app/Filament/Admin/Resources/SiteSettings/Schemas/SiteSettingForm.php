<?php

namespace App\Filament\Admin\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Image;
use Filament\Forms\Components\Select;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->label('Название компании'),

                TextInput::make('phone')
                    ->label('Телефон'),

                TextInput::make('email')
                    ->label('Email')
                    ->email(),

                TextInput::make('telegram')
                    ->label('Telegram'),

                TextInput::make('whatsapp')
                    ->label('WhatsApp'),

                TextInput::make('address')
                    ->label('Адрес'),

                Select::make('logo_image_id')
                    ->label('Логотип')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Image::query()->pluck('title', 'id')),

                Select::make('favicon_image_id')
                    ->label('Favicon')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Image::query()->pluck('title', 'id')),
            ]);
    }
}
