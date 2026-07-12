<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ComparisonSection extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [

        'name',

        'title',

        'left_title',

        'right_title',

        'left_items',

        'right_items',

    ];

    protected $casts = [

        'left_items' => 'array',

        'right_items' => 'array',

    ];
}
