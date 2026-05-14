<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SawitSuratJalan extends Model
{
    use HasFactory;

    protected $table = 'sawit_surat_jalan';

    protected $fillable = [
        'no_surat_jalan',
        'penjualan_id',
        'tanggal',
        'nama_perusahaan',
        'alamat_tujuan',
        'jenis_sawit',
        'total_kg',
        'jenis_kendaraan',
        'no_mobil',
        'nama_supir',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_kg' => 'decimal:2',
    ];

    // Relasi
    public function penjualan()
    {
        return $this->belongsTo(SawitPenjualan::class, 'penjualan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Generate nomor surat jalan otomatis
    public static function generateNoSuratJalan()
    {
        $prefix = 'SJ-SWT-';
        $date = date('Ymd');
        $lastSuratJalan = self::where('no_surat_jalan', 'like', $prefix . $date . '%')
            ->orderBy('no_surat_jalan', 'desc')
            ->first();

        if ($lastSuratJalan) {
            $lastNumber = (int) substr($lastSuratJalan->no_surat_jalan, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . '-' . $newNumber;
    }
}
