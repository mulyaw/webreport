<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class ResellerAuth
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('reseller_kode')) {
            return redirect()->route('reseller.login');
        }

        return $next($request);
    }
}

