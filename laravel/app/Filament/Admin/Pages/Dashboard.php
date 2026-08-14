<?php

namespace App\Filament\Admin\Pages;

use App\CMS\Dashboard\Services\DashboardService;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $title = 'Главная';

    protected static ?string $navigationLabel = 'Главная';

    protected static ?int $navigationSort = -100;

    protected static ?string $slug = '';

    protected string $view = 'filament.admin.pages.dashboard';

    public array $widgets = [];

    public function mount(DashboardService $dashboard): void
    {
        $dashboard->boot();

        $this->widgets = $dashboard->widgets();
    }
}