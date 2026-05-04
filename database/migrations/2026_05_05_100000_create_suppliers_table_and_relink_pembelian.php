<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $map = [];
        $supplierIds = DB::table('pembelian')->distinct()->pluck('supplier_id')->filter();

        foreach ($supplierIds as $oldUserId) {
            $user = DB::table('users')->where('id', $oldUserId)->first();
            $name = $user ? $user->name : 'Supplier #'.$oldUserId;
            $newId = DB::table('suppliers')->insertGetId([
                'name'       => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $map[(int) $oldUserId] = $newId;
        }

        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
        });

        foreach ($map as $oldUserId => $newSupplierId) {
            DB::table('pembelian')->where('supplier_id', $oldUserId)->update(['supplier_id' => $newSupplierId]);
        }

        Schema::table('pembelian', function (Blueprint $table) {
            $table->foreign('supplier_id')->references('id')->on('suppliers')->restrictOnDelete();
        });

        DB::table('users')->where('role', 'supplier')->delete();

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','gudang','sales','pelanggan') NOT NULL DEFAULT 'pelanggan'");
        }
    }

    public function down(): void
    {
        // Pembalikan tidak didukung (data supplier sudah terpisah dari users).
    }
};
