<?php

namespace App\Platform\Builder\Providers;

use App\Models\Service;

final class ServicesProvider
{
    public static function make(array $block, string $type = 'services', mixed $model = null): array
    {
        $mode = $block['mode'] ?? 'all';
        $limit = self::limit($block, $type);
        $with = self::eagerLoad();

        $services = match ($mode) {

            'featured' => Service::query()
                ->where('is_published', true)
                ->where('is_featured', true)
                ->with($with)
                ->orderBy('id')
                ->when($limit, fn ($query) => $query->limit($limit))
                ->get(),

            'manual' => collect($block['services'] ?? [])
                ->whenNotEmpty(function ($ids) use ($with) {
                    return Service::query()
                        ->whereIn('id', $ids)
                        ->where('is_published', true)
                        ->with($with)
                        ->get()
                        ->sortBy(fn (Service $service) => array_search($service->id, $ids->all()))
                        ->values();
                }, fn () => collect()),

            default => Service::query()
                ->where('is_published', true)
                ->with($with)
                ->orderBy('id')
                ->when($limit, fn ($query) => $query->limit($limit))
                ->get(),
        };

        if (! empty($block['exclude_current']) && $model instanceof Service) {
            $services = $services
                ->where('id', '!=', $model->id)
                ->values();
        }

        return [
            'block' => $block,
            'services' => $services,
        ];
    }

    private static function eagerLoad(): array
    {
        return [
            'media',
            'projects' => fn ($query) => $query
                ->where('is_published', true)
                ->with('media'),
        ];
    }

    private static function limit(array $block, string $type): ?int
    {
        if (isset($block['limit']) && $block['limit'] !== '' && $block['limit'] !== null) {
            return (int) $block['limit'];
        }

        return $type === 'services_grid' ? null : 6;
    }
}
