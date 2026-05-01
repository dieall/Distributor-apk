<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('surat_jalan', function (Blueprint $table) {
            $table->id();
            $table->string('no_sj', 30)->unique();
            $table->foreignId('permintaan_id')->constrained('permintaan_barang')->onDelete('restrict');
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('restrict');
            $table->date('tanggal');
            $table->string('driver')->nullable();
            $table->string('no_kendaraan', 20)->nullable();
            $table->string('alamat_tujuan')->nullable();
            $table->enum('status', ['dibuat','dikirim','selesai'])->default('dibuat');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
        Schema::create('surat_jalan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_jalan_id')->constrained('surat_jalan')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->decimal('jumlah', 10, 2);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('surat_jalan_detail');
        Schema::dropIfExists('surat_jalan');
    }
};
