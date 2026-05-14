<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sawit_pembelian', function (Blueprint $table) {
            $table->decimal('dp_sebelumnya', 15, 2)->default(0)->after('potongan_dp');
            $table->decimal('sisa_dp', 15, 2)->default(0)->after('dp_sebelumnya');
        });
    }

    public function down(): void
    {
        Schema::table('sawit_pembelian', function (Blueprint $table) {
            $table->dropColumn(['dp_sebelumnya', 'sisa_dp']);
        });
    }
};
