<?php

namespace App\CMS\Dashboard\Services;

use App\CMS\Dashboard\Registry\DashboardRegistry;
use App\CMS\Dashboard\Widgets\QuickActionsWidget;
use App\CMS\Dashboard\Widgets\RecentLeadsWidget;
use App\CMS\Dashboard\Widgets\StatsWidget;
use App\CMS\Dashboard\Widgets\WelcomeWidget;

class DashboardService
{
    public function __construct(
        protected DashboardRegistry $registry,
    ) {}

    public function boot(): void
    {
        $this->registry->register(WelcomeWidget::class);
        $this->registry->register(StatsWidget::class);
        $this->registry->register(RecentLeadsWidget::class);
        $this->registry->register(QuickActionsWidget::class);
    }

    public function widgets(): array
    {
        return $this->registry->all();
    }
}
