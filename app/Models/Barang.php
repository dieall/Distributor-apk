<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model {
    protected $table = 'barang';
    protected $fillable = ['kode','nama','kategori','satuan','harga_jual','harga_mbg','stok_minimum','deskripsi','is_active'];

    protected $casts = [
        'is_active' => 'boolean',
        'harga_jual' => 'decimal:2',
        'harga_mbg' => 'decimal:2',
    ];

    public function stok(): HasOne { return $this->hasOne(Stok::class); }
    public function pembelianDetail(): HasMany { return $this->hasMany(PembelianDetail::class); }
    public function penerimaanDetail(): HasMany { return $this->hasMany(PenerimaanDetail::class); }
    public function permintaanDetail(): HasMany { return $this->hasMany(PermintaanDetail::class); }
    public function stokMutasi(): HasMany { return $this->hasMany(StokMutasi::class); }

    public function getStokJumlahAttribute(): float { return $this->stok?->jumlah ?? 0; }
    public function getHargaRataAttribute(): float { return $this->stok?->harga_rata ?? 0; }
    public function isStokRendah(): bool { return $this->stok_jumlah <= $this->stok_minimum; }
}
    