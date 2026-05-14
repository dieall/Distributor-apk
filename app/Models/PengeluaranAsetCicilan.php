<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengeluaranAsetCicilan extends Model
{
    protected $table = 'pengeluaran_aset_cicilan';

    protected $fillable = [
        'pengeluaran_aset_id', 'urutan', 'tanggal', 'nominal', 'is_aktif', 'pengeluaran_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
        'is_aktif' => 'boolean',
    ];

    public function aset(): BelongsTo
    {
        return $this->belongsTo(PengeluaranAset::class, 'pengeluaran_aset_id');
    }

    public function pengeluaran(): BelongsTo
    {
        return $this->belongsTo(Pengeluaran::class);
    }
}
