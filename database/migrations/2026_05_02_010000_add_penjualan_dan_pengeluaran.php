<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('permintaan_detail', function (Blueprint $table) {
            $table->decimal('harga_jual', 15, 2)->default(0)->after('jumlah_disetujui');
            $table->decimal('subtotal_jual', 15, 2)->default(0)->after('harga_jual');
        });

        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('kategori', 80)->default('Operasional');
            $table->string('keterangan');
            $table->decimal('nominal', 15, 2);
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');

        Schema::table('permintaan_detail', function (Blueprint $table) {
            $table->dropColumn(['harga_jual', 'subtotal_jual']);
        });
    }
};
