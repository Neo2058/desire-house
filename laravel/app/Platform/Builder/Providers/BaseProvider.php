<?php

namespace App\Platform\Builder\Providers;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseProvider
{
    protected static function published(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    protected static function featured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    protected static function limit(Builder $query, array $block): Builder
    {
        return $query->limit($block['limit'] ?? 6);
    }

    protected static function manual(Builder $query, array $block, string $key = 'projects'): Builder
    {
        return $query->whereIn(
            'id',
            $block[$key] ?? []
        );
    }
}
