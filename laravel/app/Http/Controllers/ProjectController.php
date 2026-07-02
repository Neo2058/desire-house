<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ProjectController extends Controller
{
    public function __invoke(Project $project)
    {
        abort_unless($project->is_published, 404);

        return view('projects.show', compact('project'));
    }
}
