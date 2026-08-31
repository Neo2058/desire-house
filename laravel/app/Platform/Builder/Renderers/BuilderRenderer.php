<?php

namespace App\Platform\Builder\Renderers;

use App\Platform\Builder\Providers\ServicesProvider;
use App\Platform\Builder\Providers\ProjectsProvider;
use App\Platform\Builder\Providers\GalleryProvider;
use App\Platform\Builder\Providers\HeroProvider;
use App\Platform\Builder\Providers\StatsProvider;
use App\Platform\Builder\Providers\FooterProvider;
use App\Platform\Builder\Providers\InnerHeroProvider;
use App\Platform\Builder\Providers\ServiceAboutProvider;
use App\Platform\Builder\Providers\ProjectAboutProvider;
use App\Platform\Builder\Providers\ProjectGalleryProvider;
use App\Platform\Builder\Providers\WorksGalleryProvider;

class BuilderRenderer
{
    public static function render(
        array $blocks,
        mixed $model = null,
    ): array {
        return collect($blocks)
            ->map(function (array $block) use ($model) {

                $viewData = match ($block['type']) {

                    'hero' => HeroProvider::make($block['data']),

                    'services', 'services_grid' => ServicesProvider::make(
                        $block['data'],
                        $block['type'],
                        $model,
                    ),

                    'projects' => ProjectsProvider::make(
                        $block['data'],
                        $model,
                    ),

                    'gallery' => GalleryProvider::make($block['data']),

                    'stats' => StatsProvider::make($block['data']),

                    'footer' => FooterProvider::make($block['data']),

                    'inner_hero' => InnerHeroProvider::make($block['data'], $model),

                    'service_about' => ServiceAboutProvider::make($block['data'], $model),

                    'project_about' => ProjectAboutProvider::make($block['data'], $model),

                    'project_gallery' => ProjectGalleryProvider::make($block['data'], $model),

                    'works_gallery' => WorksGalleryProvider::make($block['data']),


                    default => [
                        'block' => $block['data'],
                    ],

                };



                return [
                    'type' => $block['type'],
                    'viewData' => $viewData,
                ];
            })
            ->toArray();
    }
}
