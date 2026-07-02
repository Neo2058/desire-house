<?php

namespace App\Filament\Admin\Resources\Services\Schemas;

use App\Platform\Builder\Registry\BuilderRegistry;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\Project;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),

                TextInput::make('slug')
                    ->required(),

                Textarea::make('description'),

                Select::make('projects')
                    ->label('Проекты')
                    ->multiple()
                    ->relationship('projects', 'title')
                    ->searchable()
                    ->preload(),

                Toggle::make('is_published')
                    ->default(true),

                Toggle::make('is_featured'),

                Builder::make('blocks')
                    ->blocks(BuilderRegistry::blocks())
                    ->columnSpanFull(),
            ]);
    }
}
