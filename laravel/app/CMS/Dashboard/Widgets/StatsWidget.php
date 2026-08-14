<?php

namespace App\CMS\Dashboard\Widgets;

use App\Filament\Admin\Resources\Leads\LeadResource;
use App\Filament\Admin\Resources\Pages\PageResource;
use App\Filament\Admin\Resources\Projects\ProjectResource;
use App\Filament\Admin\Resources\Services\ServiceResource;
use App\Models\Lead;
use App\Models\Page;
use App\Models\Project;
use App\Models\Service;

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
        return [
            [
                'label' => 'Страницы',
                'value' => Page::count(),
                'icon' => 'pages',
                'href' => PageResource::getUrl('index'),
            ],
            [
                'label' => 'Услуги',
                'value' => Service::count(),
                'icon' => 'services',
                'href' => ServiceResource::getUrl('index'),
            ],
            [
                'label' => 'Проекты',
                'value' => Project::count(),
                'icon' => 'projects',
                'href' => ProjectResource::getUrl('index'),
            ],
            [
                'label' => 'Заявки',
                'value' => Lead::count(),
                'icon' => 'leads',
                'href' => LeadResource::getUrl('index'),
            ],
        ];
    }
}