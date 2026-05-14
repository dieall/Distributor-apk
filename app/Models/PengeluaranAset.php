<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengeluaranAset extends Model
{
    protected $table = 'pengeluaran_aset';

    protected $fillable = [
        'keterangan', 'total_nilai', 'jumlah_bulan', 'tanggal_mulai', 'bukti_foto', 'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'total_nilai' => 'decimal:2',
    ];

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function cicilans(): HasMany
    {
        return $this->hasMany(PengeluaranAsetCicilan::class, 'pengeluaran_aset_id')->orderBy('urutan');
    }

    public function getSudahTerpostingCountAttribute(): int
    {
        return (int) $this->cicilans()->whereNotNull('pengeluaran_id')->count();
    }

    public function getSisaNilaiAttribute(): float
    {
        $posted = (float) $this->cicilans()->whereNotNull('pengeluaran_id')->sum('nominal');

        return max(0, (float) $this->total_nilai - $posted);
    }
}
