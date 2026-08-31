<?php

namespace App\Platform\Builder\Providers;

use App\Models\Project;

final class ProjectGalleryProvider extends BaseProvider
{
    public static function make(array $block, mixed $model = null): array
    {
        $images = $model instanceof Project
            ? $model->getMedia('gallery')
            : collect();

        return [
            'block' => $block,
            'images' => $images,
        ];
    }
}
