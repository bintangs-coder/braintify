<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->user() && !$request->user()->profile) {
            return redirect()->route('profile.setup');
        }

        return $next($request);
    }
}
