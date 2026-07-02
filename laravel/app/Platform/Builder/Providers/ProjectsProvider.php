<?php

namespace App\Platform\Builder\Providers;

use App\Models\Project;
use App\Models\Service;

final class ProjectsProvider extends BaseProvider
{
    public static function make(array $block, $model): array
    {
        $mode = $block['mode'] ?? 'all';

        $projects = match ($mode) {

            'featured' => Project::query()
                ->where('is_published', true)
                ->where('is_featured', true)
                ->limit($block['limit'] ?? 6)
                ->get(),

            'manual' => collect($block['projects'] ?? [])
                ->whenNotEmpty(function ($ids) {
                    return Project::query()
                        ->whereIn('id', $ids)
                        ->where('is_published', true)
                        ->get()
                        ->sortBy(fn (Project $project) => array_search($project->id, $ids->all()))
                        ->values();
                }, fn () => collect()),

            'current_service' => $model instanceof Service
                ? $model->projects()
                    ->where('is_published', true)
                    ->get()
                : collect(),

            default => Project::query()
                ->where('is_published', true)
                ->limit($block['limit'] ?? 6)
                ->get(),
        };

        return [
            'block' => $block,
            'projects' => $projects,
        ];
    }
}
