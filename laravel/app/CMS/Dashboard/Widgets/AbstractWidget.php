<?php

namespace App\CMS\Dashboard\Widgets;

use App\CMS\Dashboard\Contracts\DashboardWidget;

abstract class AbstractWidget implements DashboardWidget
{
    public function visible(): bool
    {
        return true;
    }

    public function data(): array
    {
        return [];
    }
}
