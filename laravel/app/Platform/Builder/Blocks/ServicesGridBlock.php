<?php

namespace App\Platform\Builder\Blocks;

use App\Models\Service;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

                Select::make('mode')
                    ->label('Что показывать')
                    ->options([
                        'all' => 'Все опубликованные',
                        'featured' => 'Только рекомендуемые',
                        'manual' => 'Выбрать вручную',
                    ])
                    ->default('all')
                    ->live(),

                Select::make('services')
                    ->label('Услуги')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(fn () => Service::query()
                        ->where('is_published', true)
                        ->pluck('title', 'id'))
                    ->visible(fn ($get) => $get('mode') === 'manual'),

                TextInput::make('limit')
                    ->label('Количество услуг')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->visible(fn ($get) => $get('mode') !== 'manual'),

                Toggle::make('show_description')
                    ->label('Показывать описание')
                    ->default(true),

            ]);
    }
}
