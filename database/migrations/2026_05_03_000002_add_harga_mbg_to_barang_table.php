<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->decimal('harga_mbg', 15, 2)->nullable()->after('harga_jual');
        });

        DB::table('barang')->whereNull('harga_mbg')->update([
            'harga_mbg' => DB::raw('harga_jual'),
        ]);
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('harga_mbg');
        });
    }
};
