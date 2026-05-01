<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PermintaanBarang extends Model
{
    protected $table = 'permintaan_barang';

    protected $fillable = [
        'no_permintaan', 'pelanggan_id', 'diproses_oleh',
        'tanggal_request', 'tanggal_dibutuhkan', 'status', 'catatan',
    ];

    protected $casts = [
        'tanggal_request'   => 'date',
        'tanggal_dibutuhkan' => 'date',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pelanggan_id');
    }

    public function diprosesOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(PermintaanDetail::class, 'permintaan_id');
    }

    public function suratJalan(): HasOne
    {
        return $this->hasOne(SuratJalan::class, 'permintaan_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'     => 'Pending',
            'diproses'    => 'Diproses',
            'siap_kirim'  => 'Siap Kirim',
            'selesai'     => 'Selesai',
            'dibatalkan'  => 'Dibatalkan',
            default       => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'warning',
            'diproses'   => 'info',
            'siap_kirim' => 'primary',
            'selesai'    => 'success',
            'dibatalkan' => 'danger',
            default      => 'secondary',
        };
    }
}
