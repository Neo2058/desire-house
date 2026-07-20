<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $fillable = [

        'title',

        'url',

        'page_id',

        'sort',

        'is_active',

    ];

    protected $casts = [

        'is_active' => 'boolean',

    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
