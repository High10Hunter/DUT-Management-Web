<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsLecturer
{
    public function handle(Request $request, Closure $next)
    {
        if (!isLecturer()) {
            return redirect()->back();
        }

        return $next($request);
    }
}
