<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika pengguna tidak terautentikasi (auth expired)
        if (!Auth::check()) {
            // Jika request melalui AJAX, kembalikan respons JSON
            if ($request->ajax()) {
                return response()->json(['message' => 'Session expired. Please log in again.'], 401);
            }

            // Jika bukan AJAX, arahkan ke halaman logout
            return redirect()->route('logout');
        }

        return $next($request);
    }
}
