<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembelianDetail extends Model {
    protected $table = 'pembelian_detail';
    protected $fillable = ['pembelian_id','barang_id','jumlah','harga_satuan','subtotal'];
    protected $casts = ['jumlah' => 'decimal:2', 'harga_satuan' => 'decimal:2', 'subtotal' => 'decimal:2'];

    public function pembelian(): BelongsTo { return $this->belongsTo(Pembelian::class); }
    public function barang(): BelongsTo { return $this->belongsTo(Barang::class); }
}
