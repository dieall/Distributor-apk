<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratJalanDetail extends Model
{
    protected $table = 'surat_jalan_detail';

    protected $fillable = ['surat_jalan_id', 'barang_id', 'jumlah'];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    public function suratJalan(): BelongsTo
    {
        return $this->belongsTo(SuratJalan::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
