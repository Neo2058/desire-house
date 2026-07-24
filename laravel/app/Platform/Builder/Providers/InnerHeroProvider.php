<?php

namespace App\Platform\Builder\Providers;

use App\Models\Image;

final class InnerHeroProvider extends BaseProvider
{
    public static function make(array $block): array
    {
        $background = null;

        if (!empty($block['background_image_id'])) {

            $background = Image::find(
                $block['background_image_id']
            );

        }

        return [

            'block' => $block,

            'background' => $background?->url,

        ];
    }
}
