<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk'; // sesuaikan dengan nama tabel
    public $timestamps = false; // jika tidak ada kolom created_at dan updated_at

    protected $fillable = [
        'kode', 'nama', 'harga_jual', 'nominal', 'gangguan'
    ];
}
