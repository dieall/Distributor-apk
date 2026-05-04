<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengeluaranBarang extends Model
{
    protected $table = 'pengeluaran_barang';

    public const ALASAN_OPSI = [
        'rusak_hilang'     => 'Rusak / hilang',
        'kadaluarsa'       => 'Kadaluarsa',
        'sampel_promosi'   => 'Sampel / promosi',
        'penyesuaian_stok' => 'Penyesuaian stok',
        'lainnya'          => 'Lainnya',
    ];

    protected $fillable = [
        'no_pengeluaran',
        'alasan',
        'tanggal',
        'catatan',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(PengeluaranBarangDetail::class, 'pengeluaran_barang_id');
    }

    public function getAlasanLabelAttribute(): string
    {
        return self::ALASAN_OPSI[$this->alasan] ?? $this->alasan;
    }
}
