<?php

namespace App\Platform\Builder\Providers;

use App\Models\MenuItem;

final class HeaderProvider
{
    public static function make(): array
    {
        $menu = MenuItem::query()
            ->where('is_active', true)
            ->orderBy('sort')
            ->with('page')
            ->get()
            ->map(function (MenuItem $item) {

                return [

                    'title' => $item->title,

                    'url' => $item->page
                        ? (
                        $item->page->slug === 'home'
                            ? '/'
                            : '/' . ltrim($item->page->slug, '/')
                        )
                        : $item->url,
                ];

            });

        return [

            'menu' => $menu,

        ];
    }
}
