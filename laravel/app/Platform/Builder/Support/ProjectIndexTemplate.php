<?php

namespace App\Platform\Builder\Support;

use App\Models\Project;

final class ProjectIndexTemplate
{
    public static function blocks(): array
    {
        return [
            self::innerHero(),
            [
                'type' => 'works_gallery',
                'data' => [
                    'title' => 'Все объекты',
                ],
            ],
            SiteChrome::cta(
                'Наши работы',
                'works',
                'Выберите объект-референс или оставьте заявку — рассчитаем похожий проект под ваш участок.',
            ),
            SiteChrome::footer(),
        ];
    }

    private static function innerHero(): array
    {
        $cover = Project::query()
            ->where('is_published', true)
            ->whereHas('media', fn ($query) => $query->where('collection_name', 'cover'))
            ->first();

        return [
            'type' => 'inner_hero',
            'data' => [
                'title' => 'Наши работы',
                'subtitle' => 'Реализованные объекты по направлениям',
                'show_breadcrumbs' => true,
                'background_url' => $cover?->getFirstMediaUrl('cover') ?: null,
            ],
        ];
    }
}
