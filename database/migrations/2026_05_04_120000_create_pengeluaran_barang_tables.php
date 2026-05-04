<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengeluaran_barang', function (Blueprint $table) {
            $table->id();
            $table->string('no_pengeluaran', 30)->unique();
            $table->string('alasan', 64);
            $table->date('tanggal');
            $table->text('catatan')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('pengeluaran_barang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_barang_id')->constrained('pengeluaran_barang')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');
            $table->decimal('jumlah_keluar', 10, 2);
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran_barang_detail');
        Schema::dropIfExists('pengeluaran_barang');
    }
};
