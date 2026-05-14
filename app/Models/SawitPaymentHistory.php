<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SawitPaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'sawit_payment_history';

    protected $fillable = [
        'type',
        'transaction_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'potongan',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
        'potongan' => 'decimal:2',
    ];

    // Relasi
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Polymorphic relation
    public function transaction()
    {
        if ($this->type === 'penjualan') {
            return $this->belongsTo(SawitPenjualan::class, 'transaction_id');
        } else {
            return $this->belongsTo(SawitPembelian::class, 'transaction_id');
        }
    }
}
