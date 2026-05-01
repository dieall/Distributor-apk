<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenerimaanDetail extends Model
{
    protected $table = 'penerimaan_detail';

    protected $fillable = ['penerimaan_id', 'barang_id', 'jumlah_diterima', 'harga_satuan', 'subtotal'];

    protected $casts = [
        'jumlah_diterima' => 'decimal:2',
        'harga_satuan'    => 'decimal:2',
        'subtotal'        => 'decimal:2',
    ];

    public function penerimaan(): BelongsTo
    {
        return $this->belongsTo(PenerimaanBarang::class, 'penerimaan_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
