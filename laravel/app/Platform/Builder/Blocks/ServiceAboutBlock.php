<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;

class ServiceAboutBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('service_about')
            ->label('📋 Описание услуги')
            ->schema([

                self::blockName(),

                self::title(false),

                self::subtitle(),

                self::buttonText(),

            ]);
    }
}
