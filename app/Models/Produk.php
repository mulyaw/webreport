<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk'; // nama tabel di database
    public $timestamps = false; // jika tidak ada kolom created_at, updated_at

    protected $fillable = [
        'kode', 'nama', 'harga_jual', 'harga_beli', 'stok', 'aktif', 'gangguan',
        'fisik', 'kode_operator', 'prefix_tujuan', 'nominal', 'kosong',
        'kode_hlr', 'tanpa_kode', 'harga_tetap', 'kode_area', 'catatan',
        'sms_end_user', 'postpaid', 'rumus_harga', 'qty', 'poin',
        'harga_awal', 'tgl_data', 'unit', 'urut_parsing'
    ];
}
