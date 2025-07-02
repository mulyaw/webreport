<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $request->input('entries', 10);

        $query = Produk::query();

        if ($search) {
            $query->where('kode', 'like', "%$search%")
                  ->orWhere('nama', 'like', "%$search%");
        }

        $produk = $query->orderBy('nama')->paginate($entries);

        return response()->json($produk);
    }
}
// This controller handles the retrieval of products with optional search functionality.