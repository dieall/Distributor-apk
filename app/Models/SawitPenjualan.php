<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SawitPenjualan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sawit_penjualan';

    protected $fillable = [
        'no_invoice',
        'tanggal',
        'perusahaan_id',
        'barang_id',
        'qty_timbangan',
        'refaksi',
        'selisih_timbangan',
        'total_kg_setelah_refaksi',
        'harga_per_kg',
        'total_harga',
        'status',
        'tanggal_bayar',
        'pembayaran_invoice',
        'potongan',
        'selisih_pembayaran',
        'jenis_kendaraan',
        'no_mobil',
        'nama_supir',
        'keterangan_potongan',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_bayar' => 'date',
        'qty_timbangan' => 'decimal:2',
        'refaksi' => 'decimal:2',
        'selisih_timbangan' => 'decimal:2',
        'total_kg_setelah_refaksi' => 'decimal:2',
        'harga_per_kg' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'pembayaran_invoice' => 'decimal:2',
        'potongan' => 'decimal:2',
        'selisih_pembayaran' => 'decimal:2',
    ];

    // Relasi
    public function perusahaan()
    {
        return $this->belongsTo(SawitPerusahaan::class, 'perusahaan_id');
    }

    public function barang()
    {
        return $this->belongsTo(SawitBarang::class, 'barang_id');
    }

    public function suratJalan()
    {
        return $this->hasOne(SawitSuratJalan::class, 'penjualan_id');
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
            ->where('type', 'penjualan');
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
        return $query->whereRaw('(total_harga - pembayaran_invoice - potongan) > 0');
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
        return $this->selisih_pembayaran <= 0;
    }

    // Generate nomor invoice otomatis
    public static function generateNoInvoice()
    {
        $prefix = 'INV-SWT-';
        $date = date('Ymd');
        $lastInvoice = self::where('no_invoice', 'like', $prefix . $date . '%')
            ->orderBy('no_invoice', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->no_invoice, -4);
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
            // Hitung total kg setelah refaksi
            $model->total_kg_setelah_refaksi = $model->qty_timbangan - $model->refaksi;
            
            // Hitung selisih timbangan
            $model->selisih_timbangan = $model->qty_timbangan - $model->total_kg_setelah_refaksi;
            
            // Hitung total harga
            $model->total_harga = $model->total_kg_setelah_refaksi * $model->harga_per_kg;
            
            // Hitung selisih pembayaran
            $model->selisih_pembayaran = $model->total_harga - $model->pembayaran_invoice - $model->potongan;
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
