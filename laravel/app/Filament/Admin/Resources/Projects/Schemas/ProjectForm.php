<?php

namespace App\Filament\Admin\Resources\Projects\Schemas;

use App\Filament\Admin\Resources\Concerns\SeoFields;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use App\Models\Service;
use Filament\Forms\Components\Select;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),

                TextInput::make('slug')
                    ->required(),

                TextInput::make('city'),

                TextInput::make('area')
                    ->numeric(),

                SpatieMediaLibraryFileUpload::make('cover')
                    ->collection('cover')
                    ->image(),

                SpatieMediaLibraryFileUpload::make('gallery')
                    ->collection('gallery')
                    ->multiple()
                    ->image(),

                Textarea::make('description'),

                Select::make('services')
                    ->label('Услуги')
                    ->multiple()
                    ->relationship('services', 'title')
                    ->searchable()
                    ->preload(),

                Toggle::make('is_featured'),

                Toggle::make('is_published')
                    ->default(true),

                SeoFields::make(),
            ]);
    }
}
