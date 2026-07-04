<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Schemas\Components\Fieldset;

class ComparisonBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('comparison')
            ->label('⚖️ Сравнение')
            ->schema([

                self::blockName(),

                self::title(),

                Fieldset::make('Левая колонка')
                    ->schema([

                        TextInput::make('left_title')
                            ->label('Заголовок')
                            ->required(),

                        SpatieMediaLibraryFileUpload::make('left_background')
                            ->label('Фоновое изображение')
                            ->collection('comparison-left')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxFiles(1),

                        Repeater::make('left_items')
                            ->label('Пункты')
                            ->defaultItems(5)
                            ->schema([

                                TextInput::make('text')
                                    ->label('Текст')
                                    ->required(),

                            ])
                            ->addActionLabel('Добавить пункт'),

                    ]),

                Fieldset::make('Правая колонка')
                    ->schema([

                        TextInput::make('right_title')
                            ->label('Заголовок')
                            ->required(),

                        SpatieMediaLibraryFileUpload::make('right_background')
                            ->label('Фоновое изображение')
                            ->collection('comparison-left')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxFiles(1),

                        Repeater::make('right_items')
                            ->label('Пункты')
                            ->defaultItems(5)
                            ->schema([

                                TextInput::make('text')
                                    ->label('Текст')
                                    ->required(),

                            ])
                            ->addActionLabel('Добавить пункт'),

                    ]),

            ]);
    }
}
