<?php

namespace App\Platform\Builder\Providers;

use App\Models\Project;

final class ProjectAboutProvider extends BaseProvider
{
    public static function make(array $block, mixed $model = null): array
    {
        $project = $model instanceof Project ? $model : null;
        $site = SiteSettingsProvider::make();
        $settings = $site['settings'] ?? null;
        $cover = $project?->getFirstMediaUrl('cover');

        $project?->loadMissing('services');

        return [
            'block' => $block,
            'project' => $project,
            'cover' => $cover ?: null,
            'description' => $project?->description,
            'services' => $project?->services ?? collect(),
            'phone' => $settings?->phone,
            'telegram' => $settings?->telegram,
            'whatsapp' => $settings?->whatsapp,
            'settings' => $settings,
        ];
    }
}
