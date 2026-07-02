<?php

namespace App\Platform\Builder\Blocks\Services;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

final class ServicesSchema
{
    public static function make(): Block
    {
        return Block::make('services')
            ->label('Услуги')
            ->schema([
                TextInput::make('title')
                    ->label('Заголовок')
                    ->required(),

                TextInput::make('subtitle')
                    ->label('Подзаголовок'),

                Toggle::make('auto_items')
                    ->label('Показывать опубликованные услуги')
                    ->default(true),

                TextInput::make('limit')
                    ->label('Количество услуг')
                    ->numeric()
                    ->default(6),

                TextInput::make('button_text')
                    ->label('Текст кнопки'),

                TextInput::make('button_url')
                    ->label('Ссылка'),
            ]);
    }
}
