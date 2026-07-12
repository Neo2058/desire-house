<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class StatsBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('stats')
            ->label('📊 Статистика')
            ->schema([

                self::blockName(),

                self::title(),

                self::subtitle(),

                Textarea::make('description')
                    ->label('Описание')
                    ->rows(5),

                SpatieMediaLibraryFileUpload::make('background_image_id')
                    ->label('Фоновое изображение')
                    ->collection('stats-background')
                    ->image()
                    ->maxFiles(1),

                SpatieMediaLibraryFileUpload::make('person_image_id')
                    ->label('Фотография')
                    ->collection('stats-person')
                    ->image()
                    ->maxFiles(1),

                SpatieMediaLibraryFileUpload::make('signature_image_id')
                    ->label('Подпись')
                    ->collection('stats-signature')
                    ->image()
                    ->maxFiles(1),

                Repeater::make('items')
                    ->label('Статистика')
                    ->defaultItems(3)
                    ->schema([

                        TextInput::make('number')
                            ->label('Число')
                            ->required(),

                        TextInput::make('label')
                            ->label('Описание')
                            ->required(),

                    ])
                    ->addActionLabel('Добавить показатель'),

            ]);
    }
}
