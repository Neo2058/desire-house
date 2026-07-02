<?php

namespace App\Platform\Builder\Providers;

use App\Models\Service;

final class ServicesProvider
{
    public static function make(array $block): array
    {
        $mode = $block['mode'] ?? 'all';

        $query = Service::query()
            ->where('is_published', true);

        if ($mode === 'featured') {
            $query->where('is_featured', true);
        }

        if ($mode === 'manual') {
            $services = Service::query()
                ->whereIn('id', $block['services'] ?? [])
                ->where('is_published', true)
                ->get();
        } else {
            $services = $query
                ->limit($block['limit'] ?? 6)
                ->get();
        }

        return [
            'block' => $block,
            'services' => $services,
        ];
    }
}
