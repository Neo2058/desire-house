<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;


class SiteSetting extends Model
{
    protected $fillable = [

        'company_name',

        'phone',

        'email',

        'telegram',

        'whatsapp',

        'address',

        'logo_image_id',

        'favicon_image_id',

    ];

    public function logo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Image::class, 'logo_image_id');
    }

    public function favicon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Image::class, 'favicon_image_id');
    }
}
