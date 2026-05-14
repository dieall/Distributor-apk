<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    protected $table = 'pengeluaran';

    protected $fillable = [
        'tanggal', 'kategori', 'keterangan', 'nominal', 'bukti_foto', 'dibuat_oleh', 'pengeluaran_aset_cicilan_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function asetCicilan(): BelongsTo
    {
        return $this->belongsTo(PengeluaranAsetCicilan::class, 'pengeluaran_aset_cicilan_id');
    }

    protected static function booted()
    {
        $clearDashboardCache = function () {
            \Illuminate\Support\Facades\Cache::forget('admin.dashboard.aggregate.v2');
        };

        static::created($clearDashboardCache);
        static::updated($clearDashboardCache);
        static::deleted($clearDashboardCache);
    }
}
