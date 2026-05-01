<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratJalan extends Model
{
    protected $table = 'surat_jalan';

    protected $fillable = [
        'no_sj', 'permintaan_id', 'dibuat_oleh', 'tanggal',
        'driver', 'no_kendaraan', 'alamat_tujuan', 'status', 'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(PermintaanBarang::class, 'permintaan_id');
    }

    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(SuratJalanDetail::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'dibuat'  => 'Dibuat',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
            default   => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'dibuat'  => 'warning',
            'dikirim' => 'info',
            'selesai' => 'success',
            default   => 'secondary',
        };
    }
}
