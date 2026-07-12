<?php

namespace App\Filament\Admin\Resources\ComparisonSections\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ComparisonSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Название')
                    ->required(),

                TextInput::make('title')
                    ->label('Заголовок')
                    ->required(),

                /*
                 |--------------------------------
                 | Левая сторона
                 |--------------------------------
                 */

                TextInput::make('left_title')
                    ->label('Заголовок слева')
                    ->required(),

                SpatieMediaLibraryFileUpload::make('background_left')
                    ->label('Фоновое изображение')
                    ->collection('background_left')
                    ->image()
                    ->imageEditor(),

                Repeater::make('left_items')
                    ->label('Пункты слева')
                    ->schema([

                        TextInput::make('text')
                            ->label('Текст')
                            ->required(),

                    ])
                    ->defaultItems(5)
                    ->addActionLabel('Добавить пункт'),

                /*
                 |--------------------------------
                 | Правая сторона
                 |--------------------------------
                 */

                TextInput::make('right_title')
                    ->label('Заголовок справа')
                    ->required(),

                SpatieMediaLibraryFileUpload::make('background_right')
                    ->label('Фоновое изображение')
                    ->collection('background_right')
                    ->image()
                    ->imageEditor(),

                Repeater::make('right_items')
                    ->label('Пункты справа')
                    ->schema([

                        TextInput::make('text')
                            ->label('Текст')
                            ->required(),

                    ])
                    ->defaultItems(5)
                    ->addActionLabel('Добавить пункт'),

            ]);
    }
}
