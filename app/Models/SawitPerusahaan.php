<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SawitPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'sawit_perusahaan';

    protected $fillable = [
        'nama_perusahaan',
        'alamat',
        'telepon',
        'email',
        'contact_person',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke penjualan
    public function penjualan()
    {
        return $this->hasMany(SawitPenjualan::class, 'perusahaan_id');
    }

    // Scope untuk perusahaan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
