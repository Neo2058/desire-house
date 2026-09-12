<?php

namespace App\Platform\Builder\Providers;

use App\Models\Image;
use App\Models\SiteSetting;

final class SiteSettingsProvider
{
    public static function make(): array
    {
        $settings = SiteSetting::first();

        if (! $settings) {
            return [
                'settings' => null,
                'logo' => null,
                'favicon' => null,
            ];
        }

        $logo = null;

        if ($settings->logo_image_id) {
            $logo = Image::find($settings->logo_image_id);
        }

        $favicon = null;

        if ($settings->favicon_image_id) {
            $favicon = Image::find($settings->favicon_image_id);
        }

        return [

            'settings' => $settings,

            'logo' => $logo?->url,

            'favicon' => $favicon?->url,

        ];
    }
}
