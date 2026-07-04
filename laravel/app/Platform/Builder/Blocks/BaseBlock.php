<?php

namespace App\Platform\Builder\Blocks;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Section;

abstract class BaseBlock
{
    protected static function blockName(): TextInput
    {
        return TextInput::make('block_name')
            ->label('📝 Название блока')
            ->helperText('Используется только в админ-панели')
            ->placeholder('Например: Галерея фасадов');
    }

    protected static function title(bool $required = true): TextInput
    {
        $field = TextInput::make('title')
            ->label('📢 Заголовок');

        if ($required) {
            $field->required();
        }

        return $field;
    }

    protected static function subtitle(): TextInput
    {
        return TextInput::make('subtitle')
            ->label('📄 Подзаголовок');
    }

    protected static function description(): Textarea
    {
        return Textarea::make('description')
            ->label('📝 Описание');
    }



    protected static function buttonText(): TextInput
    {
        return TextInput::make('button_text')
            ->label('🔘 Текст кнопки');
    }

    protected static function buttonUrl(): TextInput
    {
        return TextInput::make('button_url')
            ->label('🔗 Ссылка кнопки');
    }

    protected static function contentSection(array $schema): Section
    {
        return Section::make('📄 Контент')
            ->schema($schema)
            ->columns(2);
    }

    protected static function settingsSection(array $schema): Section
    {
        return Section::make('⚙️ Настройки')
            ->schema($schema);
    }

    protected static function buttonSection(): Section
    {
        return Section::make('🔘 Кнопка')
            ->schema([
                self::buttonText(),
                self::buttonUrl(),
            ])
            ->columns(2)
            ->collapsed();
    }

    protected static function section(string $title, array $schema): Fieldset
    {
        return Fieldset::make($title)
            ->schema($schema);
    }
}
