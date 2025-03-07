<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Jika user belum login
        if (!Auth::check()) {
            return redirect('login');
        }

        // Jika user login
        $user = Auth::user();

        // Jika user memiliki akses
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        return redirect('not-found');
    }
}
