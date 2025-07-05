<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ResellerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('reseller_kode')) {
            
            // PERBAIKAN: Cek apakah ini adalah request API (AJAX).
            // $request->expectsJson() akan true jika request memiliki header 'Accept: application/json'.
            // Header ini sudah kita tambahkan di file blade sebelumnya.
            if ($request->expectsJson()) {
                // Untuk request API, jangan redirect. Kirim response error 401.
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            // Untuk request browser biasa, tetap redirect ke halaman login.
            return redirect()->route('reseller.login')->withErrors([
                'login' => 'Silakan login terlebih dahulu.'
            ]);
        }

        return $next($request);
    }
}
