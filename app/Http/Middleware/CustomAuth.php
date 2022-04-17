<?php

namespace App\Http\Middleware;

use App\Plugin\Jwt;
use Closure;
use Illuminate\Http\Request;

class CustomAuth
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
        $jwt = new Jwt(env('APP_KEY'));

        if (!$request->bearerToken()) {
            return response()->json(['Message' => 'Unauthorized'], 401);
        }

        if ($jwt->check($request->bearerToken())) {
            return $next($request);
        } else {
            return response()->json(['Message' => 'Unauthorized'], 401);
        }
    }
}
