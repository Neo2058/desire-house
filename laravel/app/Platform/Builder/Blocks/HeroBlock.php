<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use App\Models\Image;
class HeroBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('hero')
            ->label('🦸 Первый экран')
            ->schema([
                self::blockName(),

                self::title(),

                self::subtitle(),

                self::description(),

                Select::make('background_image_id')
                    ->label('Фоновое изображение')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Image::query()->pluck('title', 'id')),

                Select::make('person_image_id')
                    ->label('Изображение человека')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Image::query()->pluck('title', 'id')),

                self::buttonText(),

                self::buttonUrl(),
            ]);
    }
}
