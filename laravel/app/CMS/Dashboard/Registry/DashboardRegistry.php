<?php

namespace App\CMS\Dashboard\Registry;

class DashboardRegistry
{
    protected array $widgets = [];

    public function register(string $widget): static
    {
        $this->widgets[] = $widget;

        return $this;
    }

    public function all(): array
    {
        return collect($this->widgets)
            ->unique()
            ->values()
            ->all();
    }
}
