<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;

class FeaturesBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('features')
            ->label('⭐ Преимущества')
            ->schema([

                self::blockName(),

                self::title()
                    ->default('Почему нам доверяют'),

                self::subtitle(),

                Repeater::make('items')
                    ->label('Преимущества')
                    ->schema([

                        FileUpload::make('icon')
                            ->label('Иконка (SVG)')
                            ->acceptedFileTypes([
                                'image/svg+xml',
                            ])
                            ->directory('features/icons')
                            ->disk('public'),

                        self::title()
                            ->label('Заголовок'),

                        self::description()
                            ->label('Описание'),

                    ])
                    ->addActionLabel('Добавить преимущество')
                    ->defaultItems(4)
                    ->collapsible()
                    ->reorderable(),

            ]);
    }
}
