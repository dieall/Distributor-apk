<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengeluaran_aset', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan');
            $table->decimal('total_nilai', 15, 2);
            $table->unsignedSmallInteger('jumlah_bulan');
            $table->date('tanggal_mulai');
            $table->string('bukti_foto')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pengeluaran_aset_cicilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengeluaran_aset_id')->constrained('pengeluaran_aset')->cascadeOnDelete();
            $table->unsignedSmallInteger('urutan');
            $table->date('tanggal');
            $table->decimal('nominal', 15, 2);
            $table->boolean('is_aktif')->default(true);
            $table->foreignId('pengeluaran_id')->nullable()->constrained('pengeluaran')->nullOnDelete();
            $table->timestamps();

            $table->unique(['pengeluaran_aset_id', 'urutan']);
        });

        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->foreignId('pengeluaran_aset_cicilan_id')->nullable()->after('dibuat_oleh')
                ->constrained('pengeluaran_aset_cicilan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengeluaran', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pengeluaran_aset_cicilan_id');
        });

        Schema::dropIfExists('pengeluaran_aset_cicilan');
        Schema::dropIfExists('pengeluaran_aset');
    }
};
