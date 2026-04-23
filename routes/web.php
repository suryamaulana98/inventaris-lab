<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

// Route untuk user yang belum login (guest only).
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Route untuk user terautentikasi (harus login).
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // Endpoint khusus mencatat mutasi stok masuk/keluar tanpa membuka form edit barang.
    Route::post('/items/{item}/adjust-stock', [ItemController::class, 'adjustStock'])->name('items.adjust-stock');
    // Endpoint untuk mengubah status peminjaman barang.
    Route::post('/items/{item}/borrow', [ItemController::class, 'borrow'])->name('items.borrow');
    Route::post('/items/{item}/return', [ItemController::class, 'returnItem'])->name('items.return');
    // Resource route CRUD barang: index, create, store, show, edit, update, destroy.
    Route::resource('items', ItemController::class);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
