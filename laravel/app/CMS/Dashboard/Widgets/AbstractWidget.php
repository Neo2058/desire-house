<?php

namespace App\CMS\Dashboard\Widgets;

abstract class AbstractWidget
{
    abstract public static function key(): string;

    abstract public static function title(): string;

    abstract public function data(): array;

    /**
     * Ширина виджета в сетке дашборда: full, main, side.
     */
    public static function span(): string
    {
        return 'full';
    }

    /**
     * Blade-шаблон виджета.
     */
    public function view(): string
    {
        return 'cms.dashboard.widgets.' . static::key();
    }
}