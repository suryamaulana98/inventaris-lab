@extends('layouts.app')

@section('title', 'Login - Inventaris Lab')

@section('content')
    {{-- Layout login dibagi dua: panel informasi dan form autentikasi. --}}
    <div class="auth-wrap">
        <div class="row g-4 w-100 align-items-center">
            <div class="col-lg-6">
                <div class="auth-hero h-100">
                    <span class="badge text-bg-light text-dark mb-3">Sistem Inventaris Laboratorium</span>
                    <h2 class="fw-bold mb-3">Pantau aset, stok, dan kondisi barang lebih cepat.</h2>
                    <p class="mb-4 opacity-75">
                        Masuk untuk melihat ringkasan dashboard, warning stok minimum, dan histori mutasi barang masuk/keluar.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge rounded-pill text-bg-light text-dark">Tracking Stok</span>
                        <span class="badge rounded-pill text-bg-light text-dark">Analitik Dashboard</span>
                        <span class="badge rounded-pill text-bg-light text-dark">Riwayat Mutasi</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="app-panel p-4 p-md-5">
                    <div class="mb-4">
                        <h3 class="section-title mb-1">Login Akun</h3>
                        <div class="text-muted-soft">Silakan masuk untuk mengakses panel inventaris.</div>
                    </div>

                    {{-- Form login mengirim kredensial ke route login.post. --}}
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" required>
                        </div>
                        <button class="btn btn-glow w-100 py-2">Masuk</button>
                    </form>

                    <div class="text-center mt-3 text-muted-soft">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Buat akun baru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
