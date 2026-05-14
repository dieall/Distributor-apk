<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SawitPenjual extends Model
{
    use HasFactory;

    protected $table = 'sawit_penjual';

    protected $fillable = [
        'nama_penjual',
        'alamat',
        'telepon',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke pembelian
    public function pembelian()
    {
        return $this->hasMany(SawitPembelian::class, 'penjual_id');
    }

    // Scope untuk penjual aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
