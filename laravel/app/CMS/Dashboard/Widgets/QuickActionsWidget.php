<?php

namespace App\CMS\Dashboard\Widgets;

use App\Filament\Admin\Resources\Pages\PageResource;
use App\Filament\Admin\Resources\Projects\ProjectResource;
use App\Filament\Admin\Resources\Services\ServiceResource;

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

    public static function span(): string
    {
        return 'side';
    }

    public function data(): array
    {
        return [
            [
                'label' => 'Создать страницу',
                'hint' => 'Новая страница сайта',
                'href' => PageResource::getUrl('create'),
                'icon' => 'plus',
            ],
            [
                'label' => 'Добавить услугу',
                'hint' => 'Услуга в каталоге',
                'href' => ServiceResource::getUrl('create'),
                'icon' => 'service',
            ],
            [
                'label' => 'Добавить проект',
                'hint' => 'Кейс в портфолио',
                'href' => ProjectResource::getUrl('create'),
                'icon' => 'project',
            ],
        ];
    }
}