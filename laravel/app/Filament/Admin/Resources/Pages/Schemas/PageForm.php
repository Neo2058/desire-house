<?php

namespace App\Filament\Admin\Resources\Pages\Schemas;

use App\Filament\Admin\Resources\Concerns\SeoFields;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Builder;
use App\Platform\Builder\Registry\BuilderRegistry;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {

        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),

                TextInput::make('slug')
                    ->required(),

                Toggle::make('is_published')
                    ->default(true),

                SeoFields::make(),

                Builder::make('blocks')
                    ->blocks(
                        BuilderRegistry::blocks()
                    )
                    ->columnSpanFull(),
            ]);
    }
}
