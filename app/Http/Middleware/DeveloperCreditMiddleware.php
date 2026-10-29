<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeveloperCreditMiddleware
{
    /**
     * Handle an incoming request.
     * Attaches developer credits to all HTTP responses across the whole portal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Developer', 'Sindhi Shahejad');
        $response->headers->set('X-Lead-Architect', 'Sindhi Shahejad');
        $response->headers->set('X-Portal-Author', 'Sindhi Shahejad');

        return $response;
    }
}
