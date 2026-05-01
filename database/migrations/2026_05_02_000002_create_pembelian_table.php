<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('no_po', 30)->unique();
            $table->foreignId('supplier_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('restrict');
            $table->date('tanggal');
            $table->date('tanggal_kirim_estimasi')->nullable();
            $table->enum('status', ['draft','dikirim','sebagian_diterima','diterima','dibatalkan'])->default('draft');
            $table->decimal('total', 15, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
        Schema::create('pembelian_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->decimal('jumlah', 10, 2);
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('pembelian_detail');
        Schema::dropIfExists('pembelian');
    }
};
