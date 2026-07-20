<?php

namespace App\Platform\Builder\Providers;

use App\Models\Image;

final class FooterProvider extends BaseProvider
{
    public static function make(array $block): array
    {
        $logo = null;

        if (!empty($block['logo_id'])) {
            $logo = Image::find($block['logo_id']);
        }

        return [

            'block' => $block,

            'logo' => $logo?->url,

        ];
    }
}
