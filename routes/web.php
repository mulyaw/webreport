<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResellerAuthController;
use App\Http\Middleware\ResellerAuth;
use App\Http\Middleware\RedirectIfResellerAuthenticated;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;

// Login routes (only if not authenticated)
Route::middleware([RedirectIfResellerAuthenticated::class])->group(function () {
    Route::get('/login', [ResellerAuthController::class, 'showLoginForm'])->name('reseller.login');
    Route::post('/login', [ResellerAuthController::class, 'login'])->name('reseller.login.submit');
});

// Protected routes (only if authenticated)
Route::middleware([ResellerAuth::class])->group(function () {
    Route::post('/logout', [ResellerAuthController::class, 'logout'])->name('reseller.logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Cek Produk Page View
    Route::get('/reseller/cekproduk', function () {
    return view('reseller.cekproduk');
})->middleware('reseller.auth')->name('reseller.cekproduk');

    
    // Endpoint fetch produk
    Route::get('/reseller/produk', [ProdukController::class, 'index'])->name('reseller.produk');
});
