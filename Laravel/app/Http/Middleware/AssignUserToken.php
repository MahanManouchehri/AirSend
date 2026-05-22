<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Closure;

class AssignUserToken
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->cookie('uploader_token')) {
            $token = Str::random(32);
            return $next($request)->withCookie(cookie('uploader_token', $token, 60 * 24 * 365)); // 1 year
        }
        return $next($request);
    }
}
