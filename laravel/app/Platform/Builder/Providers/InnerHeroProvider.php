<?php

namespace App\Platform\Builder\Providers;

use App\Models\Image;
use Spatie\MediaLibrary\HasMedia;

final class InnerHeroProvider extends BaseProvider
{
    public static function make(array $block, mixed $model = null): array
    {
        $background = null;

        if (!empty($block['background_image_id'])) {

            $background = Image::find(
                $block['background_image_id']
            )?->url;

        }

        if (! $background && ! empty($block['background_url'])) {
            $background = $block['background_url'];
        }

        if (! $background && $model instanceof HasMedia) {
            $cover = $model->getFirstMediaUrl('cover');
            $background = $cover ?: null;
        }

        $site = SiteSettingsProvider::make();
        $menu = MenuProvider::make();

        return [

            'block' => $block,

            'background' => $background,

            'logo' => $site['logo'] ?? null,

            'settings' => $site['settings'] ?? null,

            'phone' => $site['settings']?->phone,

            'telegram' => $site['settings']?->telegram,

            'whatsapp' => $site['settings']?->whatsapp,

            'email' => $site['settings']?->email,

            'menu' => $menu,

        ];
    }
}
