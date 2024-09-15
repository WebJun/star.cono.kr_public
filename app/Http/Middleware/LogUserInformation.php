<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogUserInformation
{
    public function handle($request, Closure $next)
    {
        $ipAddress = $request->ip();
        $userAgent = $request->header('User-Agent');
        $url = $request->fullUrl();
        Log::info("$ipAddress - $url - $userAgent");

        return $next($request);
    }
}
