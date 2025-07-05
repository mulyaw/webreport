<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $table = 'mutasi';

    protected $fillable = ['tanggal', 'keterangan', 'jumlah', 'saldo_akhir'];
}
