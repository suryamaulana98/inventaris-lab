@extends('layouts.app')

@section('title', 'Register - Inventaris Lab')

@section('content')
    {{-- Layout register: panel informasi dan form pembuatan akun baru. --}}
    <div class="auth-wrap">
        <div class="row g-4 w-100 align-items-center">
            <div class="col-lg-5 order-lg-2">
                <div class="auth-hero h-100">
                    <span class="badge text-bg-light text-dark mb-3">Buat Akun Baru</span>
                    <h2 class="fw-bold mb-3">Mulai kelola inventaris laboratorium dengan rapi.</h2>
                    <p class="mb-0 opacity-75">
                        Setelah akun dibuat, Anda bisa langsung masuk ke dashboard untuk menambah barang, mengatur stok minimum,
                        dan mencatat mutasi masuk/keluar.
                    </p>
                </div>
            </div>

            <div class="col-lg-7 order-lg-1">
                <div class="app-panel p-4 p-md-5">
                    <div class="mb-4">
                        <h3 class="section-title mb-1">Registrasi Pengguna</h3>
                        <div class="text-muted-soft">Isi data berikut untuk membuat akun.</div>
                    </div>

                    {{-- Form registrasi mengirim data user baru ke route register.post. --}}
                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-lg" required>
                            </div>
                        </div>
                        <button class="btn btn-glow w-100 py-2 mt-4">Daftar Sekarang</button>
                    </form>

                    <div class="text-center mt-3 text-muted-soft">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Login di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
