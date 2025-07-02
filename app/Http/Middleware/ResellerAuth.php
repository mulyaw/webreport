<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ResellerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('reseller_kode')) {
            return redirect()->route('reseller.login')->withErrors([
                'login' => 'Silakan login terlebih dahulu.'
            ]);
        }

        return $next($request);
    }
}
