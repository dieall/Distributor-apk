<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->unique()->constrained('barang')->onDelete('cascade');
            $table->decimal('jumlah', 10, 2)->default(0);
            $table->decimal('harga_rata', 15, 2)->default(0);
            $table->timestamps();
        });
        Schema::create('stok_mutasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->enum('tipe', ['masuk','keluar']);
            $table->decimal('jumlah', 10, 2);
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->string('referensi')->nullable();
            $table->string('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('stok_mutasi');
        Schema::dropIfExists('stok');
    }
};
