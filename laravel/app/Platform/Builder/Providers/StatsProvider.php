<?php

namespace App\Platform\Builder\Providers;

use App\Models\Image;

final class StatsProvider extends BaseProvider
{
    public static function make(array $block): array
    {
        $background = null;
        $person = null;
        $signature = null;

        if (! empty($block['background_image_id'])) {
            $background = Image::find($block['background_image_id']);
        }

        if (! empty($block['person_image_id'])) {
            $person = Image::find($block['person_image_id']);
        }

        if (! empty($block['signature_image_id'])) {
            $signature = Image::find($block['signature_image_id']);
        }

        return [

            'block' => $block,

            'background' => $background?->url,

            'person' => $person?->url,

            'signature' => $signature?->url,

        ];
    }
}
