<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reseller;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $kode = Session::get('reseller_kode');

        if (!$kode) {
            return redirect()->route('reseller.login');
        }

        $reseller = Reseller::where('kode', $kode)->first();

        if (!$reseller) {
            Session::forget('reseller_kode');
            return redirect()->route('reseller.login')->withErrors([
                'login' => 'Akun tidak ditemukan.'
            ]);
        }

        return view('reseller.dashboard', [
            'reseller' => $reseller
        ]);
    }
}
