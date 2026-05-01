<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stok extends Model
{
    protected $table = 'stok';

    protected $fillable = ['barang_id', 'jumlah', 'harga_rata'];

    protected $casts = [
        'jumlah'     => 'decimal:2',
        'harga_rata' => 'decimal:2',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
