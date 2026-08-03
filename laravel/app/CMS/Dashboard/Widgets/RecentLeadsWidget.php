<?php

namespace App\CMS\Dashboard\Widgets;

class RecentLeadsWidget extends AbstractWidget
{
    public static function key(): string
    {
        return 'recent-leads';
    }

    public static function title(): string
    {
        return 'Последние заявки';
    }

    public function data(): array
    {
        return [];
    }
}