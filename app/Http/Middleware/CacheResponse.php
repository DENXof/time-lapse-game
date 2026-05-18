<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CacheResponse
{
    public function handle($request, Closure $next, $minutes = 60)
    {
        if (!app()->environment('production')) {
            return $next($request);
        }

        $key = 'cache_' . md5($request->fullUrl());

        if (Cache::has($key)) {
            return response(Cache::get($key));
        }

        $response = $next($request);

        if ($response->isSuccessful()) {
            Cache::put($key, $response->getContent(), now()->addMinutes($minutes));
        }

        return $response;
    }
}
