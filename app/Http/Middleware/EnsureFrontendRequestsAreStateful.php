<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class EnsureFrontendRequestsAreStateful
{
    public function handle($request, Closure $next)
    {
        if ($this->fromFrontend($request)) {
            config(['session.driver' => 'cookie']);
        }

        return $next($request);
    }

    protected function fromFrontend($request)
    {
        return Str::startsWith($request->header('Referer'), config('app.url')) ||
               Str::startsWith($request->header('Origin'), config('app.url'));
    }
}
