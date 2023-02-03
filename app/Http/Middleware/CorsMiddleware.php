<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
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
        // $http_origin = $_SERVER['HTTP_ORIGIN'];
        $url = $request->url();
        $uriSegments = explode("/", parse_url($url, PHP_URL_PATH));
        // dd($uriSegments[2]);
        if ($uriSegments[2] === 'scexceldownload') {
              return $next($request);
         }
         
        $origin = request()->headers->get('origin');
        return $next($request)
            ->header('Access-Control-Allow-Origin', $origin)
            ->header('Access-Control-Allow-Methods', '*')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Headers', 'X-Requested-With,Content-Type,X-Token-Auth,Authorization')
            ->header('Accept', 'application/json');
    }
}
