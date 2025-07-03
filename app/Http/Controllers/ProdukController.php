<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function cekProdukPage()
    {
        return view('reseller.cekproduk');
    }

    public function getProduk(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1); // pagination halaman aktif

        // Buat key unik untuk setiap kombinasi search + page + perPage
        $cacheKey = "produk_cache_{$search}_{$page}_{$perPage}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $perPage) {
            $query = Produk::select('kode', 'nama', 'harga_jual', 'nominal', 'gangguan');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%$search%")
                      ->orWhere('nama', 'like', "%$search%")
                      ->orWhere('nominal', 'like', "%$search%");
                });
            }

            return $query->paginate($perPage);
        });
    }
}
