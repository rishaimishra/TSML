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
        $url = $request->url();
        $uriSegments = explode("/", parse_url($url, PHP_URL_PATH));
        // dd($uriSegments[3]);
        if ($uriSegments[2] === 'scexceldownload') {
              return $next($request);
         }
        $response = $next($request);
        $response->header('Expect-CT', 'max-age=604800, enforce');
        $response->header('Feature-Policy', 'geolocation self');

        return $response;
    }
}
