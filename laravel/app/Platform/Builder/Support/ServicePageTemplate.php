<?php

namespace App\Platform\Builder\Support;

use App\Models\Service;
use Illuminate\Support\Str;

final class ServicePageTemplate
{
    /**
     * Canonical public layout for a service page.
     *
     * Chrome (hero, about, projects, other services, CTA, footer) is assembled
     * from the Service model. Extra Builder blocks from CMS are inserted after
     * the about section, except chrome types which the template already owns.
     */
    public static function blocks(Service $service): array
    {
        $blocks = [
            self::innerHero($service),
            self::about(),
        ];

        foreach (self::extras($service) as $block) {
            $blocks[] = $block;
        }

        foreach ([
            self::projects(),
            self::otherServices(),
            SiteChrome::cta($service->title, 'service'),
            SiteChrome::footer(),
        ] as $section) {
            if (! self::hasType($blocks, $section['type'])) {
                $blocks[] = $section;
            }
        }

        return $blocks;
    }

    private static function extras(Service $service): array
    {
        $reserved = [
            'hero',
            'inner_hero',
            'service_about',
            'footer',
        ];

        return collect($service->blocks ?? [])
            ->filter(fn ($block) => is_array($block)
                && ! empty($block['type'])
                && array_key_exists('data', $block)
            )
            ->reject(fn (array $block) => in_array($block['type'], $reserved, true))
            ->values()
            ->all();
    }

    private static function hasType(array $blocks, string $type): bool
    {
        return collect($blocks)->contains(fn (array $block) => ($block['type'] ?? null) === $type);
    }

    private static function innerHero(Service $service): array
    {
        $subtitle = $service->short_description
            ?: Str::limit(strip_tags((string) $service->description), 160);

        return [
            'type' => 'inner_hero',
            'data' => [
                'title' => $service->title,
                'subtitle' => $subtitle ?: null,
                'show_breadcrumbs' => true,
                'breadcrumb_parent_title' => 'Услуги',
                'breadcrumb_parent_url' => '/uslugi',
            ],
        ];
    }

    private static function about(): array
    {
        return [
            'type' => 'service_about',
            'data' => [
                'title' => 'О направлении',
            ],
        ];
    }

    private static function projects(): array
    {
        return [
            'type' => 'projects',
            'data' => [
                'title' => 'Реализованные объекты',
                'subtitle' => 'Примеры работ по этому направлению',
                'mode' => 'current_service',
            ],
        ];
    }

    private static function otherServices(): array
    {
        return [
            'type' => 'services_grid',
            'data' => [
                'title' => 'Другие услуги',
                'subtitle' => 'Посмотрите остальные направления',
                'show_description' => true,
                'mode' => 'all',
                'exclude_current' => true,
            ],
        ];
    }

}
