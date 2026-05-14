<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SawitBarang extends Model
{
    use HasFactory;

    protected $table = 'sawit_barang';

    protected $fillable = [
        'id_sawit',
        'nama_sawit',
        'total_kg',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'total_kg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relasi ke penjualan
    public function penjualan()
    {
        return $this->hasMany(SawitPenjualan::class, 'barang_id');
    }

    // Relasi ke pembelian
    public function pembelian()
    {
        return $this->hasMany(SawitPembelian::class, 'barang_id');
    }

    // Scope untuk barang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Update total kg berdasarkan transaksi
    public function updateTotalKg()
    {
        $totalPembelian = $this->pembelian()
            ->where('status', 'done')
            ->sum('qty_setelah_refaksi');

        $totalPenjualan = $this->penjualan()
            ->where('status', 'done')
            ->sum('total_kg_setelah_refaksi');

        $this->total_kg = $totalPembelian - $totalPenjualan;
        $this->save();
    }
}
