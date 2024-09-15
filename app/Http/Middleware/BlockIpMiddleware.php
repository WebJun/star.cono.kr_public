<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 허용된 IP 목록을 불러옵니다.
        $allowedIps = config('app.allowed_ips');

        // 요청 IP가 허용된 IP 목록에 없는 경우 403 Forbidden 응답 반환
        if (!in_array($request->ip(), $allowedIps)) {
            return response('Your IP address is not allowed.', 403);
        }

        return $next($request);
    }
}
