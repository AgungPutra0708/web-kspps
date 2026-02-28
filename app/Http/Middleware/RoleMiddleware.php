<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $roleUser = Session::get('role_user');
        $rolePetugas = Session::get('role_petugas');

        // Kalau role utama tidak cocok
        if (!in_array($roleUser, $roles)) {
            return redirect()->route('login');
        }

        // Khusus petugas, cek role_petugas jika dikirim
        if ($roleUser === 'petugas') {

            // ambil semua role selain 'petugas'
            $allowedPetugasRole = array_diff($roles, ['petugas']);

            if (!empty($allowedPetugasRole) && !in_array($rolePetugas, $allowedPetugasRole)) {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
