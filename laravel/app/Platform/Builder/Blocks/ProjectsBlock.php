<?php

namespace App\Platform\Builder\Blocks;

use App\Models\Project;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ProjectsBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('projects')
            ->label('🏗 Наши проекты')
            ->schema([
                self::blockName(),

                self::title(),

                self::subtitle(),

                self::settingsSection([

                    Select::make('mode')
                        ->label('Что показывать')
                        ->options([
                            'all' => 'Все проекты',
                            'featured' => 'Только рекомендуемые',
                            'manual' => 'Выбрать вручную',
                            'current_service' => 'Связанные с текущей услугой',
                        ])
                        ->default('featured')
                        ->live(),

                    Select::make('projects')
                        ->label('Проекты')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->options(fn () => Project::query()
                            ->where('is_published', true)
                            ->pluck('title', 'id'))
                        ->visible(fn ($get) => $get('mode') === 'manual'),

                    TextInput::make('limit')
                        ->label('Количество проектов')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(100)
                        ->default(6)
                        ->visible(fn ($get) => $get('mode') !== 'manual'),

                ]),

                self::buttonSection(),
            ]);
    }
}
