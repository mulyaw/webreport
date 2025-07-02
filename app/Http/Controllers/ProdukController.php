<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function cekProdukPage()
    {
        return view('reseller.cekproduk');
    }

    public function getProduk(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $produk = Produk::select('kode', 'nama', 'harga_jual', 'nominal', 'gangguan')
            ->paginate($perPage);

        return response()->json($produk);
    }
}
