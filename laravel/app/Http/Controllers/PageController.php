<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Platform\Builder\Renderers\BuilderRenderer;

class PageController extends Controller
{
    public function __invoke(?string $slug = 'home')
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $blocks = BuilderRenderer::render(
            $page->blocks ?? []
        );

        return view('pages.page', compact(
            'page',
            'blocks',
        ));
    }
}
