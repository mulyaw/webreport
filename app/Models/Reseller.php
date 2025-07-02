<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
    protected $table = 'reseller';
    protected $primaryKey = 'kode';
    public $incrementing = false; // karena 'kode' bukan auto increment
    public $timestamps = false;

    protected $fillable = [
        'kode', 'nama', 'saldo', 'komisi', 'poin', 'pin'
    ];
}
