<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'is_published',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    protected function casts(): array
    {
        return [
            'blocks' => 'array',
        ];
    }
}
