<?php

namespace App\Platform\Builder\Providers;

use App\Models\Image;
use App\Platform\Builder\Providers\MenuProvider;

final class HeroProvider extends BaseProvider
{
    public static function make(array $block): array
    {
        $background = null;
        $person = null;

        if (!empty($block['background_image_id'])) {
            $background = Image::find($block['background_image_id']);
        }

        if (!empty($block['person_image_id'])) {
            $person = Image::find($block['person_image_id']);
        }

        $site = SiteSettingsProvider::make();

        $menu = MenuProvider::make();


        return [

            'block' => $block,

            'background' => $background?->url,

            'person' => $person?->url,

            'logo' => $site['logo'] ?? null,

            'favicon' => $site['favicon'] ?? null,

            'settings' => $site['settings'] ?? null,

            'phone' => $site['settings']?->phone,

            'telegram' => $site['settings']?->telegram,

            'whatsapp' => $site['settings']?->whatsapp,

            'email' => $site['settings']?->email,

            'menu' => $menu,

        ];
    }
}
