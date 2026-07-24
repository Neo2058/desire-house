<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Toggle;

class ServicesGridBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('services_grid')
            ->label('🧱 Каталог услуг')
            ->schema([

                self::blockName(),

                self::title(),

                self::subtitle(),

                Toggle::make('show_description')
                    ->label('Показывать описание')
                    ->default(true),

            ]);
    }
}
