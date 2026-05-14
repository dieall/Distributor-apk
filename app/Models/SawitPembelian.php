<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SawitPembelian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sawit_pembelian';

    protected $fillable = [
        'no_pembelian',
        'tanggal',
        'barang_id',
        'penjual_id',
        'nama_penjual',
        'qty_timbangan',
        'refaksi',
        'qty_setelah_refaksi',
        'harga_per_kg',
        'total_harga',
        'potongan_dp',
        'dp_sebelumnya',
        'sisa_dp',
        'tanggal_transfer',
        'selisih',
        'status',
        'type_payment',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_transfer' => 'date',
        'qty_timbangan' => 'decimal:2',
        'refaksi' => 'decimal:2',
        'qty_setelah_refaksi' => 'decimal:2',
        'harga_per_kg' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'potongan_dp' => 'decimal:2',
        'dp_sebelumnya' => 'decimal:2',
        'sisa_dp' => 'decimal:2',
        'selisih' => 'decimal:2',
    ];

    // Relasi
    public function barang()
    {
        return $this->belongsTo(SawitBarang::class, 'barang_id');
    }

    public function penjual()
    {
        return $this->belongsTo(SawitPenjual::class, 'penjual_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function paymentHistory()
    {
        return $this->hasMany(SawitPaymentHistory::class, 'transaction_id')
            ->where('type', 'pembelian');
    }

    // Scopes
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    public function scopeBelumLunas($query)
    {
        return $query->whereRaw('(total_harga - potongan_dp) > 0');
    }

    // Helpers
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isDone()
    {
        return $this->status === 'done';
    }

    public function isLunas()
    {
        return $this->selisih <= 0;
    }

    // Generate nomor pembelian otomatis
    public static function generateNoPembelian()
    {
        $prefix = 'PBL-SWT-';
        $date = date('Ymd');
        $lastPembelian = self::where('no_pembelian', 'like', $prefix . $date . '%')
            ->orderBy('no_pembelian', 'desc')
            ->first();

        if ($lastPembelian) {
            $lastNumber = (int) substr($lastPembelian->no_pembelian, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . '-' . $newNumber;
    }

    // Hitung otomatis
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Hitung qty setelah refaksi
            $model->qty_setelah_refaksi = $model->qty_timbangan - $model->refaksi;
            
            // Hitung total harga
            $model->total_harga = $model->qty_setelah_refaksi * $model->harga_per_kg;
            
            // Hitung selisih = Total Harga - Potongan DP
            $model->selisih = $model->total_harga - $model->potongan_dp;

            // Hitung sisa DP = Selisih - DP Sebelumnya
            $model->sisa_dp = $model->selisih - $model->dp_sebelumnya;
        });

        static::saved(function ($model) {
            // Update stok barang jika status done
            if ($model->status === 'done') {
                $model->barang->updateTotalKg();
            }
        });

        static::deleted(function ($model) {
            // Update stok barang setelah dihapus
            $model->barang->updateTotalKg();
        });
    }
}
