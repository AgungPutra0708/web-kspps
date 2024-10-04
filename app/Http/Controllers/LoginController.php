<?php

namespace App\Http\Controllers;

use App\Models\UserMemberModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);

            // Mencari pengguna berdasarkan username
            $user = UserMemberModel::where('username', $request->username)->first();

            // Memeriksa apakah pengguna ada dan password cocok
            if ($user && Hash::check($request->password, $user->password)) {
                // Jika password cocok, login pengguna
                Auth::login($user);

                // Simpan role ke session
                Session::put('role', $user->status);
                $role = Session::get('role');

                // Redirect berdasarkan role
                if ($role == 'petugas') {
                    return redirect()->route('dashboard');
                } elseif ($role == 'anggota') {
                    return redirect()->route('home');
                }
            }

            // Jika autentikasi gagal
            return redirect()->back()->with('error', 'Login gagal, coba lagi');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan, silakan coba lagi!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
