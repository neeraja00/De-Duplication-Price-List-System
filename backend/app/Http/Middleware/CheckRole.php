<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role ?? 'user';

        // Check if the route is intended for admin but the user is not an admin
        if ($role === 'admin' && $userRole !== 'admin') {
            return redirect()->route('user.dashboard');
        }

        // Check if the route is intended for user but the user is an admin
        if ($role === 'user' && $userRole === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Proceed if roles match
        if ($role === $userRole) {
            return $next($request);
        }

        // Failsafe
        abort(403, 'Unauthorized action.');
    }
}
