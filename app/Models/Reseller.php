<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Reseller extends Authenticatable
{
    protected $table = 'reseller';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'kode', 'nama', 'saldo', 'komisi', 'poin', 'pin'
    ];
}
