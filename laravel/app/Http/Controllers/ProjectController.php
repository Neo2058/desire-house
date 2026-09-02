<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Platform\Builder\Renderers\BuilderRenderer;
use App\Platform\Builder\Support\ProjectPageTemplate;

class ProjectController extends Controller
{
    public function __invoke(Project $project)
    {
        abort_unless($project->is_published, 404);

        $project->load('services');

        $blocks = BuilderRenderer::render(
            ProjectPageTemplate::blocks($project),
            $project,
        );

        return view(
            'projects.show',
            [
                'project' => $project,
                'blocks' => $blocks,
                'seo' => $project,
            ]
        );
    }
}
