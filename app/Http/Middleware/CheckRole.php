<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class CheckRole
{
    public function handle($request, Closure $next, $role): Response
    {
        if (Auth::check() && Auth::user()->hasRole($role)) {
            return $next($request);
        }

        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}

