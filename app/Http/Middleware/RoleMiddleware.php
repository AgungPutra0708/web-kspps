<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        // Retrieve the role from the session
        $userRole = Session::get('role_user');

        // Debugging: Check what role is being retrieved
        if ($userRole !== $role) {
            return redirect()->route('login'); // Redirect if role doesn't match
        }

        return $next($request); // Proceed to the next request if everything is fine
    }
}
