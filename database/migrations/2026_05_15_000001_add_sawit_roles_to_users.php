<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah enum role untuk menambahkan 3 role sawit baru
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'direktur', 'gudang', 'sales', 'purchasing', 'pelanggan', 'adminsawit', 'accountingsawit', 'direktursawit') NOT NULL DEFAULT 'pelanggan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum sebelumnya (hapus role sawit)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'direktur', 'gudang', 'sales', 'purchasing', 'pelanggan') NOT NULL DEFAULT 'pelanggan'");
    }
};
