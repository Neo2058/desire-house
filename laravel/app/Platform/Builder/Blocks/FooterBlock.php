<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use App\Models\Image;

class FooterBlock extends BaseBlock
{
    public static function make(): Block
    {
        return Block::make('footer')
            ->label('⚫ Футер')
            ->schema([

                self::blockName(),

                TextInput::make('company')
                    ->label('Название компании')
                    ->required(),

                Select::make('logo_id')
                    ->label('Логотип')
                    ->searchable()
                    ->preload()
                    ->options(fn () => Image::query()->pluck('title', 'id')),

                Textarea::make('description')
                    ->label('Описание')
                    ->rows(3),

                TextInput::make('phone')
                    ->label('Телефон'),

                TextInput::make('email')
                    ->label('E-mail'),

                TextInput::make('telegram')
                    ->label('Telegram'),

                TextInput::make('whatsapp')
                    ->label('WhatsApp'),

                TextInput::make('copyright')
                    ->label('Копирайт'),

                TextInput::make('privacy_url')
                    ->label('Ссылка на политику'),

                Repeater::make('menu')
                    ->label('Меню')
                    ->schema([

                        TextInput::make('title')
                            ->label('Название'),

                        TextInput::make('url')
                            ->label('Ссылка'),

                    ])
                    ->defaultItems(5)

            ]);
    }
}
