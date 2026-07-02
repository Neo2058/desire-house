<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class CtaBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('cta')
            ->label('📢 Оставьте заявку')
            ->schema([
                self::blockName(),

                self::title(),

                self::subtitle(),

                Textarea::make('description'),

                self::buttonText(),

                self::buttonUrl(),
            ]);
    }
}
