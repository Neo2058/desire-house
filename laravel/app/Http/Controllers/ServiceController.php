<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Platform\Builder\Renderers\BuilderRenderer;

class ServiceController extends Controller
{
    public function __invoke(Service $service)
    {
        abort_unless(
            $service->is_published,
            404
        );

        $blocks = BuilderRenderer::render(
            $service->blocks ?? [],
            $service,
        );

        return view(
            'builder.service',
            compact(
                'service',
                'blocks',
            )
        );
    }
}
