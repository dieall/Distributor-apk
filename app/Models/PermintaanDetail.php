<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermintaanDetail extends Model
{
    protected $table = 'permintaan_detail';

    protected $fillable = [
        'permintaan_id', 'barang_id',
        'jumlah_diminta', 'jumlah_disetujui', 'harga_jual', 'subtotal_jual', 'is_checked',
    ];

    protected $casts = [
        'jumlah_diminta'   => 'decimal:2',
        'jumlah_disetujui' => 'decimal:2',
        'harga_jual'       => 'decimal:2',
        'subtotal_jual'    => 'decimal:2',
        'is_checked'       => 'boolean',
    ];

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(PermintaanBarang::class, 'permintaan_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
