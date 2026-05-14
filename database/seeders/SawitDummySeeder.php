<?php

namespace Database\Seeders;

use App\Models\SawitBarang;
use App\Models\SawitPerusahaan;
use App\Models\SawitPenjualan;
use App\Models\SawitPembelian;
use App\Models\SawitSuratJalan;
use App\Models\User;
use Illuminate\Database\Seeder;

class SawitDummySeeder extends Seeder
{
    public function run(): void
    {
        $adminSawit = User::where('role', 'adminsawit')->first();
        $userId = $adminSawit?->id;

        // ===== BARANG SAWIT =====
        $this->command->info('Membuat data barang sawit...');
        $barang = [
            ['id_sawit' => 'SWT-0001', 'nama_sawit' => 'TBS (Tandan Buah Segar)', 'keterangan' => 'Buah sawit segar dari kebun', 'is_active' => true],
            ['id_sawit' => 'SWT-0002', 'nama_sawit' => 'CPO (Crude Palm Oil)', 'keterangan' => 'Minyak sawit mentah', 'is_active' => true],
            ['id_sawit' => 'SWT-0003', 'nama_sawit' => 'Kernel (Inti Sawit)', 'keterangan' => 'Biji inti sawit', 'is_active' => true],
            ['id_sawit' => 'SWT-0004', 'nama_sawit' => 'PKO (Palm Kernel Oil)', 'keterangan' => 'Minyak inti sawit', 'is_active' => true],
            ['id_sawit' => 'SWT-0005', 'nama_sawit' => 'Cangkang Sawit', 'keterangan' => 'Cangkang biji sawit untuk bahan bakar', 'is_active' => true],
        ];

        foreach ($barang as $b) {
            SawitBarang::firstOrCreate(['id_sawit' => $b['id_sawit']], $b);
        }

        $tbs = SawitBarang::where('id_sawit', 'SWT-0001')->first();
        $cpo = SawitBarang::where('id_sawit', 'SWT-0002')->first();
        $kernel = SawitBarang::where('id_sawit', 'SWT-0003')->first();

        // ===== PERUSAHAAN =====
        $this->command->info('Membuat data perusahaan...');
        $perusahaanData = [
            ['nama_perusahaan' => 'PT. Sinar Mas Agro', 'alamat' => 'Jl. Industri No. 10, Medan', 'telepon' => '061-1234567', 'email' => 'info@sinarmas.co.id', 'contact_person' => 'Budi Santoso', 'is_active' => true],
            ['nama_perusahaan' => 'PT. Wilmar Nabati', 'alamat' => 'Jl. Pelabuhan No. 5, Dumai', 'telepon' => '0765-432100', 'email' => 'purchase@wilmar.co.id', 'contact_person' => 'Ahmad Fauzi', 'is_active' => true],
            ['nama_perusahaan' => 'PT. Musim Mas', 'alamat' => 'Jl. Raya Pekanbaru Km 12', 'telepon' => '0761-555888', 'email' => 'order@musimmas.com', 'contact_person' => 'Dewi Lestari', 'is_active' => true],
            ['nama_perusahaan' => 'PT. Asian Agri', 'alamat' => 'Jl. Lintas Sumatera No. 88, Jambi', 'telepon' => '0741-667788', 'email' => 'procurement@asianagri.com', 'contact_person' => 'Hendra Wijaya', 'is_active' => true],
            ['nama_perusahaan' => 'PT. Astra Agro Lestari', 'alamat' => 'Jl. Sudirman No. 200, Jakarta', 'telepon' => '021-9876543', 'email' => 'supply@astra-agro.co.id', 'contact_person' => 'Rina Susanti', 'is_active' => true],
        ];

        $perusahaan = [];
        foreach ($perusahaanData as $p) {
            $perusahaan[] = SawitPerusahaan::firstOrCreate(['nama_perusahaan' => $p['nama_perusahaan']], $p);
        }

        // ===== PEMBELIAN SAWIT =====
        $this->command->info('Membuat data pembelian sawit...');
        $pembelianData = [
            ['tanggal' => '2026-05-01', 'barang_id' => $tbs->id, 'nama_penjual' => 'Pak Joko - Kebun Sei Mangkei', 'qty_timbangan' => 15000, 'refaksi' => 450, 'harga_per_kg' => 2850, 'potongan_dp' => 5000000, 'dp_sebelumnya' => 0, 'status' => 'done', 'type_payment' => 'transfer'],
            ['tanggal' => '2026-05-02', 'barang_id' => $tbs->id, 'nama_penjual' => 'Koperasi Sawit Makmur', 'qty_timbangan' => 22000, 'refaksi' => 660, 'harga_per_kg' => 2800, 'potongan_dp' => 10000000, 'dp_sebelumnya' => 5000000, 'status' => 'done', 'type_payment' => 'transfer'],
            ['tanggal' => '2026-05-03', 'barang_id' => $tbs->id, 'nama_penjual' => 'H. Rahman - Kebun Langkat', 'qty_timbangan' => 18500, 'refaksi' => 555, 'harga_per_kg' => 2900, 'potongan_dp' => 8000000, 'dp_sebelumnya' => 3000000, 'status' => 'done', 'type_payment' => 'cash'],
            ['tanggal' => '2026-05-05', 'barang_id' => $kernel->id, 'nama_penjual' => 'PT. Kebun Nusantara III', 'qty_timbangan' => 8000, 'refaksi' => 160, 'harga_per_kg' => 5200, 'potongan_dp' => 15000000, 'dp_sebelumnya' => 10000000, 'status' => 'done', 'type_payment' => 'tempo'],
            ['tanggal' => '2026-05-07', 'barang_id' => $tbs->id, 'nama_penjual' => 'Pak Sugianto - Kebun Asahan', 'qty_timbangan' => 12000, 'refaksi' => 360, 'harga_per_kg' => 2850, 'potongan_dp' => 0, 'dp_sebelumnya' => 0, 'status' => 'done', 'type_payment' => 'cash'],
            ['tanggal' => '2026-05-08', 'barang_id' => $cpo->id, 'nama_penjual' => 'CV. Sawit Jaya Mandiri', 'qty_timbangan' => 5000, 'refaksi' => 50, 'harga_per_kg' => 14500, 'potongan_dp' => 20000000, 'dp_sebelumnya' => 15000000, 'status' => 'done', 'type_payment' => 'transfer'],
            ['tanggal' => '2026-05-10', 'barang_id' => $tbs->id, 'nama_penjual' => 'Kelompok Tani Harapan Baru', 'qty_timbangan' => 25000, 'refaksi' => 750, 'harga_per_kg' => 2750, 'potongan_dp' => 12000000, 'dp_sebelumnya' => 8000000, 'status' => 'done', 'type_payment' => 'transfer'],
            ['tanggal' => '2026-05-12', 'barang_id' => $tbs->id, 'nama_penjual' => 'Pak Darmawan - Kebun Simalungun', 'qty_timbangan' => 9500, 'refaksi' => 285, 'harga_per_kg' => 2900, 'potongan_dp' => 0, 'dp_sebelumnya' => 0, 'status' => 'draft', 'type_payment' => 'cash'],
            ['tanggal' => '2026-05-13', 'barang_id' => $kernel->id, 'nama_penjual' => 'UD. Sawit Berkah', 'qty_timbangan' => 6000, 'refaksi' => 120, 'harga_per_kg' => 5100, 'potongan_dp' => 5000000, 'dp_sebelumnya' => 0, 'status' => 'draft', 'type_payment' => 'tempo'],
            ['tanggal' => '2026-05-14', 'barang_id' => $tbs->id, 'nama_penjual' => 'Pak Irwan - Kebun Deli Serdang', 'qty_timbangan' => 16000, 'refaksi' => 480, 'harga_per_kg' => 2850, 'potongan_dp' => 7000000, 'dp_sebelumnya' => 3000000, 'status' => 'draft', 'type_payment' => 'transfer'],
        ];

        $noCounter = 1;
        foreach ($pembelianData as $data) {
            $data['no_pembelian'] = 'PBL-SWT-2026050' . str_pad($noCounter, 2, '0', STR_PAD_LEFT) . '-0001';
            $data['created_by'] = $userId;
            SawitPembelian::firstOrCreate(['no_pembelian' => $data['no_pembelian']], $data);
            $noCounter++;
        }

        // ===== PENJUALAN SAWIT =====
        $this->command->info('Membuat data penjualan sawit...');
        $penjualanData = [
            ['tanggal' => '2026-05-02', 'perusahaan_id' => $perusahaan[0]->id, 'barang_id' => $tbs->id, 'qty_timbangan' => 20000, 'refaksi' => 600, 'harga_per_kg' => 3100, 'status' => 'done', 'pembayaran_invoice' => 55000000, 'potongan' => 2000000, 'tanggal_bayar' => '2026-05-05', 'jenis_kendaraan' => 'Truk Fuso', 'no_mobil' => 'BK 1234 AB', 'nama_supir' => 'Supardi'],
            ['tanggal' => '2026-05-03', 'perusahaan_id' => $perusahaan[1]->id, 'barang_id' => $cpo->id, 'qty_timbangan' => 10000, 'refaksi' => 100, 'harga_per_kg' => 15500, 'status' => 'done', 'pembayaran_invoice' => 150000000, 'potongan' => 3000000, 'tanggal_bayar' => '2026-05-07', 'jenis_kendaraan' => 'Tangki', 'no_mobil' => 'BK 5678 CD', 'nama_supir' => 'Bambang'],
            ['tanggal' => '2026-05-05', 'perusahaan_id' => $perusahaan[2]->id, 'barang_id' => $tbs->id, 'qty_timbangan' => 30000, 'refaksi' => 900, 'harga_per_kg' => 3050, 'status' => 'done', 'pembayaran_invoice' => 80000000, 'potongan' => 1500000, 'tanggal_bayar' => '2026-05-10', 'jenis_kendaraan' => 'Truk Tronton', 'no_mobil' => 'BK 9012 EF', 'nama_supir' => 'Agus Salim'],
            ['tanggal' => '2026-05-07', 'perusahaan_id' => $perusahaan[3]->id, 'barang_id' => $kernel->id, 'qty_timbangan' => 5000, 'refaksi' => 75, 'harga_per_kg' => 6200, 'status' => 'done', 'pembayaran_invoice' => 30000000, 'potongan' => 500000, 'tanggal_bayar' => null, 'jenis_kendaraan' => 'Truk Engkel', 'no_mobil' => 'BK 3456 GH', 'nama_supir' => 'Dedi'],
            ['tanggal' => '2026-05-09', 'perusahaan_id' => $perusahaan[4]->id, 'barang_id' => $tbs->id, 'qty_timbangan' => 18000, 'refaksi' => 540, 'harga_per_kg' => 3000, 'status' => 'done', 'pembayaran_invoice' => 0, 'potongan' => 0, 'tanggal_bayar' => null, 'jenis_kendaraan' => 'Truk Fuso', 'no_mobil' => 'BK 7890 IJ', 'nama_supir' => 'Hasan'],
            ['tanggal' => '2026-05-10', 'perusahaan_id' => $perusahaan[0]->id, 'barang_id' => $cpo->id, 'qty_timbangan' => 8000, 'refaksi' => 80, 'harga_per_kg' => 15800, 'status' => 'done', 'pembayaran_invoice' => 125000000, 'potongan' => 0, 'tanggal_bayar' => '2026-05-13', 'jenis_kendaraan' => 'Tangki', 'no_mobil' => 'BK 2345 KL', 'nama_supir' => 'Rudi'],
            ['tanggal' => '2026-05-12', 'perusahaan_id' => $perusahaan[1]->id, 'barang_id' => $tbs->id, 'qty_timbangan' => 25000, 'refaksi' => 750, 'harga_per_kg' => 3100, 'status' => 'draft', 'pembayaran_invoice' => 0, 'potongan' => 0, 'tanggal_bayar' => null, 'jenis_kendaraan' => 'Truk Tronton', 'no_mobil' => 'BK 6789 MN', 'nama_supir' => 'Joko'],
            ['tanggal' => '2026-05-13', 'perusahaan_id' => $perusahaan[2]->id, 'barang_id' => $kernel->id, 'qty_timbangan' => 4000, 'refaksi' => 60, 'harga_per_kg' => 6000, 'status' => 'draft', 'pembayaran_invoice' => 0, 'potongan' => 0, 'tanggal_bayar' => null, 'jenis_kendaraan' => 'Truk Engkel', 'no_mobil' => 'BK 0123 OP', 'nama_supir' => 'Wahyu'],
            ['tanggal' => '2026-05-14', 'perusahaan_id' => $perusahaan[3]->id, 'barang_id' => $tbs->id, 'qty_timbangan' => 15000, 'refaksi' => 450, 'harga_per_kg' => 3050, 'status' => 'draft', 'pembayaran_invoice' => 0, 'potongan' => 0, 'tanggal_bayar' => null, 'jenis_kendaraan' => 'Truk Fuso', 'no_mobil' => 'BK 4567 QR', 'nama_supir' => 'Eko'],
        ];

        $invCounter = 1;
        $penjualanRecords = [];
        foreach ($penjualanData as $data) {
            $data['no_invoice'] = 'INV-SWT-2026050' . str_pad($invCounter, 2, '0', STR_PAD_LEFT) . '-0001';
            $data['created_by'] = $userId;
            $penjualanRecords[] = SawitPenjualan::firstOrCreate(['no_invoice' => $data['no_invoice']], $data);
            $invCounter++;
        }

        // ===== SURAT JALAN =====
        $this->command->info('Membuat data surat jalan...');
        $donePenjualan = SawitPenjualan::where('status', 'done')->get();

        $sjCounter = 1;
        foreach ($donePenjualan->take(4) as $pj) {
            SawitSuratJalan::firstOrCreate(
                ['penjualan_id' => $pj->id],
                [
                    'no_surat_jalan' => 'SJ-SWT-2026050' . str_pad($sjCounter, 2, '0', STR_PAD_LEFT) . '-0001',
                    'penjualan_id' => $pj->id,
                    'tanggal' => $pj->tanggal,
                    'nama_perusahaan' => $pj->perusahaan->nama_perusahaan,
                    'alamat_tujuan' => $pj->perusahaan->alamat ?? 'Alamat Perusahaan',
                    'jenis_sawit' => $pj->barang->nama_sawit,
                    'total_kg' => $pj->total_kg_setelah_refaksi,
                    'jenis_kendaraan' => $pj->jenis_kendaraan,
                    'no_mobil' => $pj->no_mobil,
                    'nama_supir' => $pj->nama_supir,
                    'keterangan' => 'Pengiriman sawit ke ' . $pj->perusahaan->nama_perusahaan,
                    'created_by' => $userId,
                ]
            );
            $sjCounter++;
        }

        // ===== UPDATE STOK BARANG =====
        $this->command->info('Mengupdate stok barang...');
        foreach (SawitBarang::all() as $brg) {
            $brg->updateTotalKg();
        }

        $this->command->info('');
        $this->command->info('✅ Data dummy sawit berhasil dibuat!');
        $this->command->info('   - 5 Barang Sawit');
        $this->command->info('   - 5 Perusahaan');
        $this->command->info('   - 10 Pembelian (7 Done, 3 Draft)');
        $this->command->info('   - 9 Penjualan (6 Done, 3 Draft)');
        $this->command->info('   - 4 Surat Jalan');
        $this->command->info('');
    }
}
