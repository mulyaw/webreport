<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Ganti Auth dengan Session
use Illuminate\Support\Facades\Session; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MutasiTransaksiController extends Controller
{
    public function index()
    {
        return view('reseller.MutasiTransaksi');
    }

    public function getData(Request $request)
    {
        // PERBAIKAN: Cek login menggunakan Session, bukan Auth
        if (!Session::has('reseller_kode')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Ambil kode reseller dari Session
        $kodeReseller = Session::get('reseller_kode');
        
        $startDate = $request->input('start');
        $endDate = $request->input('end');

        $query = DB::table('mutasi')
            ->select('tanggal', 'keterangan', 'jumlah', 'saldo_akhir')
            ->where('jenis', 'T')
            ->where('kode_reseller', $kodeReseller);

        if ($startDate && $endDate) {
            $query->whereBetween('tanggal', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }
        
        $data = $query->orderBy('tanggal', 'desc')->get();

        return response()->json($data);
    }
}
