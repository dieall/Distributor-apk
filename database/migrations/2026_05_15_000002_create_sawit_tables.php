<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel Barang Sawit
        Schema::create('sawit_barang', function (Blueprint $table) {
            $table->id();
            $table->string('id_sawit')->unique();
            $table->string('nama_sawit');
            $table->decimal('total_kg', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Perusahaan/Pembeli
        Schema::create('sawit_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_person')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Penjual (untuk pembelian)
        Schema::create('sawit_penjual', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penjual');
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel Penjualan Sawit
        Schema::create('sawit_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->date('tanggal');
            $table->foreignId('perusahaan_id')->constrained('sawit_perusahaan')->onDelete('restrict');
            $table->foreignId('barang_id')->constrained('sawit_barang')->onDelete('restrict');
            $table->decimal('qty_timbangan', 15, 2);
            $table->decimal('refaksi', 15, 2)->default(0);
            $table->decimal('selisih_timbangan', 15, 2)->default(0);
            $table->decimal('total_kg_setelah_refaksi', 15, 2);
            $table->decimal('harga_per_kg', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->enum('status', ['draft', 'done'])->default('draft');
            $table->date('tanggal_bayar')->nullable();
            $table->decimal('pembayaran_invoice', 15, 2)->default(0);
            $table->decimal('potongan', 15, 2)->default(0);
            $table->decimal('selisih_pembayaran', 15, 2)->nullable();
            $table->string('jenis_kendaraan')->nullable();
            $table->string('no_mobil')->nullable();
            $table->string('nama_supir')->nullable();
            $table->text('keterangan_potongan')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Pembelian Sawit
        Schema::create('sawit_pembelian', function (Blueprint $table) {
            $table->id();
            $table->string('no_pembelian')->unique();
            $table->date('tanggal');
            $table->foreignId('barang_id')->constrained('sawit_barang')->onDelete('restrict');
            $table->foreignId('penjual_id')->constrained('sawit_penjual')->onDelete('restrict');
            $table->decimal('qty_timbangan', 15, 2);
            $table->decimal('refaksi', 15, 2)->default(0);
            $table->decimal('qty_setelah_refaksi', 15, 2);
            $table->decimal('harga_per_kg', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->decimal('potongan_dp', 15, 2)->default(0);
            $table->date('tanggal_transfer')->nullable();
            $table->decimal('selisih', 15, 2)->default(0);
            $table->enum('status', ['draft', 'done'])->default('draft');
            $table->enum('type_payment', ['cash', 'transfer', 'tempo'])->default('cash');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel Surat Jalan
        Schema::create('sawit_surat_jalan', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat_jalan')->unique();
            $table->foreignId('penjualan_id')->constrained('sawit_penjualan')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nama_perusahaan');
            $table->text('alamat_tujuan');
            $table->string('jenis_sawit');
            $table->decimal('total_kg', 15, 2);
            $table->string('jenis_kendaraan');
            $table->string('no_mobil');
            $table->string('nama_supir');
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Tabel Payment History (untuk tracking pembayaran)
        Schema::create('sawit_payment_history', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['penjualan', 'pembelian']);
            $table->unsignedBigInteger('transaction_id');
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->decimal('potongan', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sawit_payment_history');
        Schema::dropIfExists('sawit_surat_jalan');
        Schema::dropIfExists('sawit_pembelian');
        Schema::dropIfExists('sawit_penjualan');
        Schema::dropIfExists('sawit_penjual');
        Schema::dropIfExists('sawit_perusahaan');
        Schema::dropIfExists('sawit_barang');
    }
};
