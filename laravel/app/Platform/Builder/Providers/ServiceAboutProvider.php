<?php

namespace App\Platform\Builder\Providers;

use App\Models\Service;

final class ServiceAboutProvider extends BaseProvider
{
    public static function make(array $block, mixed $model = null): array
    {
        $service = $model instanceof Service ? $model : null;
        $site = SiteSettingsProvider::make();
        $settings = $site['settings'] ?? null;
        $cover = $service?->getFirstMediaUrl('cover');

        return [
            'block' => $block,
            'service' => $service,
            'cover' => $cover ?: null,
            'description' => $service?->description,
            'phone' => $settings?->phone,
            'telegram' => $settings?->telegram,
            'whatsapp' => $settings?->whatsapp,
            'settings' => $settings,
        ];
    }
}
