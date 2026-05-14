<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model {
    protected $table = 'pembelian';
    protected $fillable = ['no_po','supplier_id','dibuat_oleh','tanggal','tanggal_kirim_estimasi','status','total','catatan','bukti_pembayaran'];
    protected $casts = ['tanggal' => 'date', 'tanggal_kirim_estimasi' => 'date', 'total' => 'decimal:2', 'bukti_pembayaran' => 'array'];

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class, 'supplier_id'); }
    public function dibuatOleh(): BelongsTo { return $this->belongsTo(User::class, 'dibuat_oleh'); }
    public function detail(): HasMany { return $this->hasMany(PembelianDetail::class); }
    public function penerimaan(): HasMany { return $this->hasMany(PenerimaanBarang::class); }

    public function getStatusLabelAttribute(): string {
        return match($this->status) {
            'draft' => 'Draft', 'dikirim' => 'Dikirim', 'sebagian_diterima' => 'Sebagian Diterima',
            'diterima' => 'Diterima', 'dibatalkan' => 'Dibatalkan', default => $this->status,
        };
    }
    public function getStatusColorAttribute(): string {
        return match($this->status) {
            'draft' => 'warning', 'dikirim' => 'info', 'sebagian_diterima' => 'primary',
            'diterima' => 'success', 'dibatalkan' => 'danger', default => 'secondary',
        };
    }
}
