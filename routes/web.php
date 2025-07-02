<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResellerAuthController;
use App\Http\Middleware\ResellerAuth;
use App\Http\Middleware\RedirectIfResellerAuthenticated;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;

// Halaman login reseller
Route::middleware([RedirectIfResellerAuthenticated::class])->group(function () {
    Route::get('/login', [ResellerAuthController::class, 'showLoginForm'])->name('reseller.login');
    Route::post('/login', [ResellerAuthController::class, 'login'])->name('reseller.login.submit');
});

// Setelah login
Route::middleware([ResellerAuth::class])->group(function () {
    Route::post('/logout', [ResellerAuthController::class, 'logout'])->name('reseller.logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/reseller/cekproduk', [ProdukController::class, 'cekProdukPage'])->name('reseller.cekproduk');
    Route::get('/api/reseller/produk', [ProdukController::class, 'getProduk'])->name('reseller.cekproduk.data');
});
// Route untuk halaman utama reseller