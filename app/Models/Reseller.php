<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
    protected $table = 'reseller';
    public $timestamps = false;

    protected $fillable = ['kode', 'pin'];
}
