<?php

namespace App\Platform\Builder\Blocks;

use App\Models\Gallery;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class GalleryBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('gallery')
            ->label('🖼 Галерея')
            ->schema([
                self::blockName(),

                self::title(),

                self::subtitle(),

                Select::make('gallery')
                    ->label('Галерея')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Gallery::query()
                        ->where('is_published', true)
                        ->pluck('title', 'id'))
                    ->required(),

                Select::make('columns')
                    ->label('Количество колонок')
                    ->options([
                        2 => '2',
                        3 => '3',
                        4 => '4',
                    ])
                    ->default(3),

                Toggle::make('lightbox')
                    ->label('Открывать изображения')
                    ->default(true),
            ]);
    }
}
