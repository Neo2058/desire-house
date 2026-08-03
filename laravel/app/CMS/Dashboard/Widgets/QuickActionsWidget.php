<?php

namespace App\CMS\Dashboard\Widgets;

class QuickActionsWidget extends AbstractWidget
{
    public static function key(): string
    {
        return 'quick-actions';
    }

    public static function title(): string
    {
        return 'Быстрые действия';
    }

    public function data(): array
    {
        return [];
    }
}