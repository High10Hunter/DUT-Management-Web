<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsStudent
{
    public function handle(Request $request, Closure $next)
    {
        if (!isStudent()) {
            return redirect()->back();
        }

        return $next($request);
    }
}
