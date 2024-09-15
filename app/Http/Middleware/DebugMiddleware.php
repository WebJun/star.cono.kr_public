<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

// 특정 아이피에서만 debug 모드 활성화
class DebugMiddleware
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
        $allowedIPs = [
            '172.16.0.1', // 집
            '210.217.16.23', // 커넥트웨이브 카비오스
        ];

        if (in_array($request->ip(), $allowedIPs)) {
            Config::set('app.debug', true);
        }

        return $next($request);
    }
}
