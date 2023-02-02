<?php

namespace App\Http\Middleware;

use Closure;

class AddHeadersToResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        // $response->header('Expect-CT', 'max-age=604800, enforce');
        // $response->header('Feature-Policy', 'geolocation self');

        return $response;
    }
}
