<?php

namespace App\Http\Middleware;

use App\Services\SetupState;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSetupIsAvailable
{
    public function __construct(private readonly SetupState $setupState) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->setupState->hasSuperAdmin()) {
            return redirect('/admin');
        }

        abort_unless(config('setup.enabled'), 404);
        abort_if(config('setup.token') === '', 503, 'Install token is not configured.');
        abort_unless($this->setupState->migrationsAreReady(), 503, 'Database migrations are not complete.');

        return $next($request);
    }
}
