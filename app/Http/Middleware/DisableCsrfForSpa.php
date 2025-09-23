<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableCsrfForSpa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow API routes to bypass CSRF (they use Sanctum tokens)
        if ($request->is('api/*')) {
            return $next($request);
        }
        
        // For SPA requests to web routes, ensure CSRF token is available
        if ($request->header('Accept') === 'application/json' && 
            !$request->is('api/*')) {
            // Let the request continue to CSRF validation
            // The token should be in the request headers or form data
        }
        
        return $next($request);
    }
}
