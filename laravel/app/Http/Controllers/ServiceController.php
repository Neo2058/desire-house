<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Platform\Builder\Renderers\BuilderRenderer;
use App\Platform\Builder\Support\ServicePageTemplate;

class ServiceController extends Controller
{
    public function __invoke(Service $service)
    {
        abort_unless(
            $service->is_published,
            404
        );

        $blocks = BuilderRenderer::render(
            ServicePageTemplate::blocks($service),
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
