<?php

namespace App\Http\Controllers;

use App\Platform\Builder\Renderers\BuilderRenderer;
use App\Platform\Builder\Support\ProjectIndexTemplate;
use App\Platform\Seo\SiteSeo;

class ProjectIndexController extends Controller
{
    public function __invoke()
    {
        $blocks = BuilderRenderer::render(
            ProjectIndexTemplate::blocks(),
        );

        return view('projects.index', [
            'blocks' => $blocks,
            'seo' => SiteSeo::worksIndexData(),
        ]);
    }
}
