<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class MobileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $agent = new Agent();

        // Check if the user is on a mobile device
        if (!$agent->isMobile()) {
            // If not mobile, redirect or return an error
            return redirect()->route('login')->with('error', 'Halaman ini hanya dapat diakses oleh mobile device');
        }
        return $next($request);
    }
}
