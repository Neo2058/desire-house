<?php

namespace App\CMS\Dashboard\Widgets;

class WelcomeWidget extends AbstractWidget
{
    public static function key(): string
    {
        return 'welcome';
    }

    public static function title(): string
    {
        return 'Добро пожаловать';
    }

    public function data(): array
    {
        return [

            'title' => 'Desire House CMS',

            'subtitle' => 'Добро пожаловать в систему управления сайтом.',

            'date' => now(),

        ];
    }
}
