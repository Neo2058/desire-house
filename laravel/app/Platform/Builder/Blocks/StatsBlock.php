<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

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

                Repeater::make('items')
                    ->schema([
                        TextInput::make('number')
                            ->required(),

                        TextInput::make('label')
                            ->required(),
                    ])
            ]);
    }
}
