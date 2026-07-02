<?php

namespace App\Platform\Builder\Providers;

use App\Models\Gallery;

final class GalleryProvider
{
    public static function make(array $block): array
    {
        $gallery = Gallery::find($block['gallery']);

        return [
            'block' => $block,
            'gallery' => $gallery,
        ];
    }
}
