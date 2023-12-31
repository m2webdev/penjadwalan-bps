<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjadwalan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function jadwal():BelongsTo
    {
        return $this->belongsTo(Jadwal::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kultum(): BelongsTo
    {
        return $this->belongsTo(Kultum::class);
    }

    public function infografis():BelongsTo
    {
        return $this->belongsTo(Infografis::class);
    }
}
