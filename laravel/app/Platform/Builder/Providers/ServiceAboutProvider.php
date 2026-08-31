<?php

namespace App\Platform\Builder\Providers;

use App\Models\Service;

final class ServiceAboutProvider extends BaseProvider
{
    public static function make(array $block, mixed $model = null): array
    {
        $service = $model instanceof Service ? $model : null;
        $site = SiteSettingsProvider::make();
        $cover = $service?->getFirstMediaUrl('cover');

        return [
            'block' => $block,
            'service' => $service,
            'cover' => $cover ?: null,
            'description' => $service?->description,
            'phone' => $site['settings']?->phone,
            'telegram' => $site['settings']?->telegram,
            'whatsapp' => $site['settings']?->whatsapp,
            'settings' => $site['settings'] ?? null,
        ];
    }
}
