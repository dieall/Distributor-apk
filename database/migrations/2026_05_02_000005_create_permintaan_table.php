<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('permintaan_barang', function (Blueprint $table) {
            $table->id();
            $table->string('no_permintaan', 30)->unique();
            $table->foreignId('pelanggan_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->date('tanggal_request');
            $table->date('tanggal_dibutuhkan')->nullable();
            $table->enum('status', ['pending','diproses','siap_kirim','selesai','dibatalkan'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
        Schema::create('permintaan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')->constrained('permintaan_barang')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->decimal('jumlah_diminta', 10, 2);
            $table->decimal('jumlah_disetujui', 10, 2)->default(0);
            $table->boolean('is_checked')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('permintaan_detail');
        Schema::dropIfExists('permintaan_barang');
    }
};
