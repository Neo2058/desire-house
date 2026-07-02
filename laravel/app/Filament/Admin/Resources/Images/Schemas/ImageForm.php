<?php

namespace App\Filament\Admin\Resources\Images\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('title')
                    ->label('Название')
                    ->required(),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),

                TextInput::make('alt')
                    ->label('ALT'),

                SpatieMediaLibraryFileUpload::make('image')
                    ->label('Изображение')
                    ->collection('image')
                    ->image()
                    ->required(),

            ]);
    }
}
