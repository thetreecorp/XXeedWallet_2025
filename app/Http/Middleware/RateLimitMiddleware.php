<?php

// app/Http/Middleware/RateLimitMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class RateLimitMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $visitorId = $request->ip(); // or any other unique identifier for the visitor
        $cacheKey = "rate_limit_{$visitorId}";

        if (Cache::has($cacheKey)) {
            $calls = Cache::get($cacheKey);
            if ($calls >= 2) {
                return response()->json(['error' => 'Rate limit exceeded'], 429);
            }
        } else {
            $calls = 0;
        }

        $response = $next($request);

        Cache::put($cacheKey, $calls + 1, now()->addMinutes(2));

        return $response;
    }
}
