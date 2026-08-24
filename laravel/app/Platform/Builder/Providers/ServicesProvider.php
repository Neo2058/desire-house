<?php

namespace App\Platform\Builder\Providers;

use App\Models\Service;

final class ServicesProvider
{
    public static function make(array $block): array
    {
        $mode = $block['mode'] ?? 'all';

        $query = Service::query()
            ->with(['projects' => fn ($query) => $query
                ->where('is_published', true)
                ->with('media')])
            ->where('is_published', true);

        if ($mode === 'featured') {
            $query->where('is_featured', true);
        }

        if ($mode === 'manual') {
            $services = Service::query()
                ->with(['projects' => fn ($query) => $query
                    ->where('is_published', true)
                    ->with('media')])
                ->whereIn('id', $block['services'] ?? [])
                ->where('is_published', true)
                ->get()
                ->sortBy(fn (Service $service) => array_search(
                    $service->id,
                    $block['services'] ?? [],
                ))
                ->values();
        } else {
            $services = $query
                ->orderBy('id')
                ->limit($block['limit'] ?? 6)
                ->get();
        }

        return [
            'block' => $block,
            'services' => $services,
        ];
    }
}
