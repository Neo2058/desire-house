<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Models\Image;
use Filament\Forms\Components\Select;

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

                Select::make('background_image_id')
                    ->label('Фоновое изображение')
                    ->searchable()
                    ->preload()
                    ->options(fn () =>
                    Image::query()
                        ->pluck('title','id')
                    ),

                Select::make('person_image_id')
                    ->label('Фотография')
                    ->searchable()
                    ->preload()
                    ->options(fn () =>
                    Image::query()
                        ->pluck('title','id')
                    ),

                Select::make('signature_image_id')
                    ->label('Подпись')
                    ->searchable()
                    ->preload()
                    ->options(fn () =>
                    Image::query()
                        ->pluck('title','id')
                    ),

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
