<?php

namespace App\Platform\Builder\Blocks;

use App\Models\Image;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class InnerHeroBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('inner_hero')
            ->label('📄 Внутренний Hero')
            ->schema([

                self::blockName(),

                self::title(),

                self::subtitle(),

                Select::make('background_image_id')
                    ->label('Фоновое изображение')
                    ->searchable()
                    ->preload()
                    ->options(
                        fn () => Image::query()->pluck('title', 'id')
                    ),

                Toggle::make('show_breadcrumbs')
                    ->label('Показывать хлебные крошки')
                    ->default(true),

            ]);
    }
}
