<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class JsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
