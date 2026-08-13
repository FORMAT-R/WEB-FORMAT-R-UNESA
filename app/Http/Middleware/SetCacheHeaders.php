<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCacheHeaders
{
    /**
     * Handle an incoming request.
     * Sets safe, efficient HTTP Cache-Control headers for Laravel responses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Jangan cache area admin atau request non-GET
        if ($request->is('admin*') || !$request->isMethodCacheable()) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
            return $response;
        }

        // Atur browser cache 1 hari (86400 detik) untuk halaman frontend publik
        if (!$response->headers->has('Cache-Control') || str_contains($response->headers->get('Cache-Control', ''), 'no-cache')) {
            if ($request->routeIs('home')) { $response->headers->set('Cache-Control', 'no-cache, private, must-revalidate'); } else { $response->headers->set('Cache-Control', 'public, max-age=86400, s-maxage=86400'); }
        }

        return $response;
    }
}
