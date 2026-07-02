<?php

namespace App\Platform\Builder\Renderers;

use App\Platform\Builder\Providers\ServicesProvider;
use App\Platform\Builder\Providers\ProjectsProvider;
use App\Platform\Builder\Providers\GalleryProvider;
use App\Platform\Builder\Providers\HeroProvider;

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

                    'services' => ServicesProvider::make($block['data']),

                    'projects' => ProjectsProvider::make(
                        $block['data'],
                        $model,
                    ),

                    'gallery' => GalleryProvider::make($block['data']),


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
