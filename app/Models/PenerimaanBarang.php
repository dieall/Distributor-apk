<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenerimaanBarang extends Model {
    protected $table = 'penerimaan_barang';
    protected $fillable = ['no_penerimaan','pembelian_id','diterima_oleh','tanggal','status','catatan'];
    protected $casts = ['tanggal' => 'date'];

    public function pembelian(): BelongsTo { return $this->belongsTo(Pembelian::class); }
    public function diterima(): BelongsTo { return $this->belongsTo(User::class, 'diterima_oleh'); }
    public function detail(): HasMany { return $this->hasMany(PenerimaanDetail::class, 'penerimaan_id'); }
}
