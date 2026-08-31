<?php

namespace App\Platform\Builder\Support;

use App\Models\Project;

final class ProjectPageTemplate
{
    /**
     * Canonical public layout for a project page.
     * Reuses the same Builder chrome as service pages.
     */
    public static function blocks(Project $project): array
    {
        $project->loadMissing('services');

        return [
            self::innerHero($project),
            self::about(),
            self::gallery(),
            self::otherProjects(),
            SiteChrome::cta($project->title, 'project'),
            SiteChrome::footer(),
        ];
    }

    private static function innerHero(Project $project): array
    {
        $parts = array_filter([
            $project->city,
            $project->area ? $project->area_label : null,
        ]);

        $parent = $project->services->first();

        return [
            'type' => 'inner_hero',
            'data' => [
                'title' => $project->title,
                'subtitle' => $parts ? implode(' · ', $parts) : null,
                'show_breadcrumbs' => true,
                'breadcrumb_parent_title' => $parent?->title,
                'breadcrumb_parent_url' => $parent ? '/uslugi/'.$parent->slug : null,
            ],
        ];
    }

    private static function about(): array
    {
        return [
            'type' => 'project_about',
            'data' => [
                'title' => 'О объекте',
            ],
        ];
    }

    private static function gallery(): array
    {
        return [
            'type' => 'project_gallery',
            'data' => [
                'title' => 'Фото объекта',
            ],
        ];
    }

    private static function otherProjects(): array
    {
        return [
            'type' => 'projects',
            'data' => [
                'title' => 'Другие работы',
                'subtitle' => 'Ещё объекты из портфолио',
                'mode' => 'all',
                'exclude_current' => true,
            ],
        ];
    }
}
