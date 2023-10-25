<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjadwalan extends Model
{
    use HasFactory;
    protected $fillable = [
        'tanggal_jadwal',
        'user_id',
        'jadwal_id'
    ];
}
