<?php

use RalphJSmit\Laravel\SEO\Models\SEO;

return [
    'model' => SEO::class,

    'site_name' => env('APP_NAME'),

    'sitemap' => '/sitemap.xml',

    'canonical_link' => true,

    'robots' => [
        'default' => 'max-snippet:-1,max-image-preview:large,max-video-preview:-1',
        'force_default' => false,
    ],

    'favicon' => 'favicon.ico',

    'title' => [
        'infer_title_from_url' => false,
        'suffix' => '',
        'homepage_title' => null,
    ],

    'description' => [
        'fallback' => null,
    ],

    'image' => [
        'fallback' => null,
    ],

    'author' => [
        'fallback' => null,
    ],

    'twitter' => [
        '@username' => null,
    ],
];
