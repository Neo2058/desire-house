<?php

namespace App\Platform\Builder\Providers;

use App\Models\Project;

final class ProjectAboutProvider extends BaseProvider
{
    public static function make(array $block, mixed $model = null): array
    {
        $project = $model instanceof Project ? $model : null;
        $site = SiteSettingsProvider::make();
        $cover = $project?->getFirstMediaUrl('cover');

        $project?->loadMissing('services');

        return [
            'block' => $block,
            'project' => $project,
            'cover' => $cover ?: null,
            'description' => $project?->description,
            'services' => $project?->services ?? collect(),
            'phone' => $site['settings']?->phone,
            'telegram' => $site['settings']?->telegram,
            'whatsapp' => $site['settings']?->whatsapp,
            'settings' => $site['settings'] ?? null,
        ];
    }
}
