<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\FileUpload;

class CtaBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('cta')
            ->label('📩 Заявка')
            ->schema([

                self::blockName(),

                self::title(),

                self::subtitle(),

                Textarea::make('description')
                    ->label('Описание')
                    ->rows(3),

                TextInput::make('button_text')
                    ->label('Текст кнопки')
                    ->default('Получить расчёт'),

                FileUpload::make('background')
                    ->label('Фоновое изображение')
                    ->image()
                    ->directory('cta'),

                Select::make('object_types')
                    ->label('Типы объектов')
                    ->multiple()
                    ->options([
                        'Фундамент' => 'Фундамент',
                        'Дом'      => 'Дом',
                        'Гараж'     => 'Гараж',
                        'Баня'  => 'Баня',
                        'Пристройка'  => 'Пристройка',
                        'Терраса'  => 'Терраса',
                        'Другое'      => 'Другое',
                    ])
                    ->default([
                        'foundation',
                        'house',
                        'garage',
                        'bathhouse',
                    ]),
            ]);
    }
}
