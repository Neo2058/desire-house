<?php

namespace App\Platform\Builder\Providers;

use App\Models\MenuItem;

final class MenuProvider
{
    public static function make(): array
    {
        return MenuItem::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->get()
            ->map(function ($item) {

                return [

                    'title' => $item->title,

                    'url' => $item->resolved_url,
                ];

            })
            ->toArray();
    }
}
