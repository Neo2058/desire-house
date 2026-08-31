<?php

namespace App\Platform\Builder\Providers;

use App\Models\Project;
use App\Models\Service;

final class WorksGalleryProvider extends BaseProvider
{
    public static function make(array $block): array
    {
        $services = Service::query()
            ->where('is_published', true)
            ->whereHas('projects', fn ($query) => $query->where('is_published', true))
            ->with(['projects' => function ($query) {
                $query->where('is_published', true)
                    ->with('media')
                    ->orderBy('id');
            }])
            ->orderBy('id')
            ->get();

        $groups = $services
            ->map(fn (Service $service) => [
                'title' => $service->title,
                'slug' => $service->slug,
                'url' => '/uslugi/'.$service->slug,
                'projects' => $service->projects,
            ])
            ->filter(fn (array $group) => $group['projects']->isNotEmpty())
            ->values();

        $ungrouped = Project::query()
            ->where('is_published', true)
            ->whereDoesntHave('services')
            ->with('media')
            ->orderBy('id')
            ->get();

        if ($ungrouped->isNotEmpty()) {
            $groups->push([
                'title' => 'Другие работы',
                'slug' => 'drugie',
                'url' => null,
                'projects' => $ungrouped,
            ]);
        }

        return [
            'block' => $block,
            'groups' => $groups,
        ];
    }
}
