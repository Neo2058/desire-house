<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Platform\Builder\Renderers\BuilderRenderer;
use App\Services\SetupState;

class PageController extends Controller
{
    public function __invoke(SetupState $setupState, ?string $slug = 'home')
    {
        if ($slug === 'home' && $setupState->isAvailable()) {
            return redirect()->route('setup.show');
        }

        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $blocks = BuilderRenderer::render(
            $page->blocks ?? []
        );

        return view('pages.page', [
            'page' => $page,
            'blocks' => $blocks,
            'seo' => $page,
        ]);
    }
}
