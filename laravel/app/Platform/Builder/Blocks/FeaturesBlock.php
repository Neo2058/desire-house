<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class FeaturesBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('features')
            ->label('⭐ Преимущества')
            ->schema([
                self::blockName(),

                self::title(),

                self::subtitle(),

                Repeater::make('items')
                    ->schema([
                        TextInput::make('title')
                            ->required(),

                        Textarea::make('description'),
                    ])
            ]);
    }
}
