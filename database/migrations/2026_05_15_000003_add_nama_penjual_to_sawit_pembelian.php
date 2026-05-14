<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sawit_pembelian', function (Blueprint $table) {
            $table->string('nama_penjual')->after('penjual_id')->nullable();
            // Buat penjual_id nullable karena sekarang nama diinput manual
            $table->foreignId('penjual_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sawit_pembelian', function (Blueprint $table) {
            $table->dropColumn('nama_penjual');
        });
    }
};
