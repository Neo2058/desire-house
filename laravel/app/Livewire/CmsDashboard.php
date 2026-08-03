<?php

namespace App\Livewire;

use Livewire\Component;
use App\CMS\Dashboard\Services\DashboardService;

class CmsDashboard extends Component
{
    public array $widgets = [];

    public function mount(DashboardService $dashboard): void
    {
        $dashboard->boot();

        $this->widgets = $dashboard->widgets();
    }

    public function render()
    {
        return view('components.cms-dashboard');
    }
}