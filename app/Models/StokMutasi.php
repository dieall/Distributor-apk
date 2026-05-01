<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokMutasi extends Model
{
    protected $table = 'stok_mutasi';

    protected $fillable = [
        'barang_id', 'tipe', 'jumlah', 'harga_satuan',
        'referensi', 'keterangan', 'user_id',
    ];

    protected $casts = [
        'jumlah'       => 'decimal:2',
        'harga_satuan' => 'decimal:2',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
