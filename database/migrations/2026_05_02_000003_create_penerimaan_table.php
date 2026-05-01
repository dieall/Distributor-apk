<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penerimaan_barang', function (Blueprint $table) {
            $table->id();
            $table->string('no_penerimaan', 30)->unique();
            $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('restrict');
            $table->foreignId('diterima_oleh')->constrained('users')->onDelete('restrict');
            $table->date('tanggal');
            $table->enum('status', ['pending','selesai'])->default('selesai');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
        Schema::create('penerimaan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_id')->constrained('penerimaan_barang')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->decimal('jumlah_diterima', 10, 2);
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('penerimaan_detail');
        Schema::dropIfExists('penerimaan_barang');
    }
};
