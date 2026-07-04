<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HeroSection extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [

        'name',

        'title',

        'subtitle',

        'description',

        'button_text',

        'button_url',

    ];
}
