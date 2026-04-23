<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Menampilkan halaman form login.
        return view('auth.login');
    }

    public function showRegister()
    {
        // Menampilkan halaman form registrasi user baru.
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        // Validasi data login sebelum dicek ke sistem autentikasi Laravel.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Jika autentikasi gagal, kembalikan ke form dengan error.
        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        // Regenerasi session mencegah session fixation setelah login berhasil.
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function register(Request $request): RedirectResponse
    {
        // Validasi data registrasi, termasuk konfirmasi password.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Simpan user baru lalu langsung login otomatis.
        $user = User::create($data);

        Auth::login($user);
        // Regenerasi session agar identitas login menggunakan session baru.
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        // Hapus sesi autentikasi user saat ini.
        Auth::logout();

        // Invalidasi session dan token CSRF untuk keamanan saat logout.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
