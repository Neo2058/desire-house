<?php

namespace App\CMS\Dashboard\Widgets;

class StatsWidget extends AbstractWidget
{
    public static function key(): string
    {
        return 'stats';
    }

    public static function title(): string
    {
        return 'Статистика';
    }

    public function data(): array
    {
        return [];
    }
}