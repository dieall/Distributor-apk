<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengeluaranBarangDetail extends Model
{
    protected $table = 'pengeluaran_barang_detail';

    protected $fillable = [
        'pengeluaran_barang_id',
        'barang_id',
        'jumlah_keluar',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_keluar' => 'decimal:2',
    ];

    public function pengeluaranBarang(): BelongsTo
    {
        return $this->belongsTo(PengeluaranBarang::class, 'pengeluaran_barang_id');
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
