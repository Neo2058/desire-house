<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Lead extends Model
{

    protected $fillable = [

        'name',

        'phone',

        'object_type',

        'message',

        'source',

        'status',

        'processed',

        'admin_comment',

    ];


    protected $casts = [

        'processed'=>'boolean',

    ];


}
