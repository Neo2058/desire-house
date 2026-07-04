<?php

namespace App\Filament\Admin\Resources\HeroSections\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class HeroSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Название (для администратора)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('title')
                    ->label('Заголовок')
                    ->required()
                    ->maxLength(255),

                TextInput::make('subtitle')
                    ->label('Подзаголовок')
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Описание')
                    ->rows(4),

                SpatieMediaLibraryFileUpload::make('background')
                    ->label('Фоновое изображение')
                    ->collection('background')
                    ->image()
                    ->imageEditor(),

                SpatieMediaLibraryFileUpload::make('person')
                    ->label('Изображение человека')
                    ->collection('person')
                    ->image()
                    ->imageEditor(),

                TextInput::make('button_text')
                    ->label('Текст кнопки'),

                TextInput::make('button_url')
                    ->label('Ссылка кнопки'),

            ]);
    }
}
