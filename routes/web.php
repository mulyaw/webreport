<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResellerAuthController;
use App\Http\Middleware\ResellerAuth;
use App\Http\Middleware\RedirectIfResellerAuthenticated;

Route::middleware([RedirectIfResellerAuthenticated::class])->group(function () {
    Route::get('/login', [ResellerAuthController::class, 'showLoginForm'])->name('reseller.login');
    Route::post('/login', [ResellerAuthController::class, 'login'])->name('reseller.login.submit');
});

Route::middleware([ResellerAuth::class])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::post('/logout', [ResellerAuthController::class, 'logout'])->name('reseller.logout');
});