<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class VerifyToken {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $getToken = Redis::get("token:" . $request->bearerToken());
        if (!$getToken || !User::find(json_decode($getToken)->_id)){
            return response("Unauthenticated", 403);
        }
        return $next($request);
    }
}
