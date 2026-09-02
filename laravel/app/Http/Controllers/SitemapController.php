<?php

namespace App\Http\Controllers;

use App\Platform\Seo\SitemapBuilder;

class SitemapController extends Controller
{
    public function __invoke()
    {
        return SitemapBuilder::make()->toResponse(request());
    }
}
