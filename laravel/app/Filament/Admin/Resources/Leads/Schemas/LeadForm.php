<?php

namespace App\Filament\Admin\Resources\Leads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Имя'),


                TextInput::make('phone')
                    ->label('Телефон')
                    ->required(),


                TextInput::make('object_type')
                    ->label('Тип объекта'),


                Select::make('status')
                    ->label('Статус')
                    ->options([

                        'new'=>'Новая',

                        'work'=>'В работе',

                        'clarification'=>'На уточнении',

                        'payment'=>'Ожидает оплаты',

                        'done'=>'Завершена',

                    ])
                    ->required(),


                Toggle::make('processed')
                    ->label('Обработано'),


                Textarea::make('admin_comment')
                    ->label('Комментарий')
                    ->rows(5),
            ]);
    }
}
