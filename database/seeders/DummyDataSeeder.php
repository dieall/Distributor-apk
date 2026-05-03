<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\PenerimaanBarang;
use App\Models\PenerimaanDetail;
use App\Models\PermintaanBarang;
use App\Models\PermintaanDetail;
use App\Models\Pengeluaran;
use App\Models\Stok;
use App\Models\StokMutasi;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@distributor.com')->first();
        $supplier = User::where('email', 'supplier@distributor.com')->first();
        $gudang = User::where('email', 'gudang@distributor.com')->first();
        $sales = User::where('email', 'sales@distributor.com')->first();
        $pelanggan = User::where('email', 'pelanggan@distributor.com')->first();

        if (! $admin || ! $supplier || ! $gudang || ! $sales || ! $pelanggan) {
            $this->command->error('Jalankan UserSeeder terlebih dahulu.');

            return;
        }

        $barangs = Barang::orderBy('id')->take(5)->get();
        if ($barangs->count() < 2) {
            $this->command->error('Minimal 2 barang diperlukan. Jalankan BarangSeeder.');

            return;
        }

        DB::transaction(function () use ($admin, $supplier, $gudang, $sales, $pelanggan, $barangs) {
            $b = [
                $barangs[0],
                $barangs[1],
                $barangs[min(2, $barangs->count() - 1)],
            ];

            $this->seedPembelianDanPenerimaan($admin, $supplier, $gudang, $b);
            $this->seedPengeluaran($admin);
            $this->seedPermintaanDanSuratJalan($sales, $pelanggan, $b);
        });

        $this->command->info('Data demo (PO, penerimaan, permintaan, surat jalan, pengeluaran) selesai.');
    }

    private function seedPembelianDanPenerimaan(User $admin, User $supplier, User $gudang, array $b): void
    {
        $po1 = Pembelian::firstOrCreate(
            ['no_po' => 'SEED-PO-0001'],
            [
                'supplier_id' => $supplier->id,
                'dibuat_oleh' => $admin->id,
                'tanggal' => now()->subDays(7)->toDateString(),
                'tanggal_kirim_estimasi' => now()->subDays(2)->toDateString(),
                'status' => 'draft',
                'total' => 0,
                'catatan' => 'Contoh PO draft (demo seeder).',
            ]
        );
        if ($po1->detail()->count() === 0) {
            $lines = [
                ['barang' => $b[0], 'jumlah' => 40, 'harga' => 5000],
                ['barang' => $b[1], 'jumlah' => 25, 'harga' => 12000],
            ];
            $total = $this->attachPembelianDetails($po1, $lines);
            $po1->update(['total' => $total]);
        }

        $po2 = Pembelian::firstOrCreate(
            ['no_po' => 'SEED-PO-0002'],
            [
                'supplier_id' => $supplier->id,
                'dibuat_oleh' => $admin->id,
                'tanggal' => now()->subDays(10)->toDateString(),
                'tanggal_kirim_estimasi' => now()->subDays(4)->toDateString(),
                'status' => 'dikirim',
                'total' => 0,
                'catatan' => 'Contoh PO dikirim + penerimaan gudang.',
            ]
        );
        if ($po2->detail()->count() === 0) {
            $lines = [
                ['barang' => $b[0], 'jumlah' => 100, 'harga' => 4800],
            ];
            $total = $this->attachPembelianDetails($po2, $lines);
            $po2->update(['total' => $total]);
        }

        $pn = PenerimaanBarang::firstOrCreate(
            ['no_penerimaan' => 'SEED-PN-0001'],
            [
                'pembelian_id' => $po2->id,
                'diterima_oleh' => $gudang->id,
                'tanggal' => now()->subDays(3)->toDateString(),
                'status' => 'selesai',
                'catatan' => 'Penerimaan demo untuk SEED-PO-0002.',
            ]
        );
        if ($pn->detail()->count() === 0) {
            foreach ($po2->detail as $d) {
                $sub = $d->jumlah * $d->harga_satuan;
                PenerimaanDetail::create([
                    'penerimaan_id' => $pn->id,
                    'barang_id' => $d->barang_id,
                    'jumlah_diterima' => $d->jumlah,
                    'harga_satuan' => $d->harga_satuan,
                    'subtotal' => $sub,
                ]);
            }
        }
    }

    /**
     * @param  array<int, array{barang: Barang, jumlah: float|int, harga: float|int}>  $lines
     */
    private function attachPembelianDetails(Pembelian $po, array $lines): float
    {
        $total = 0;
        foreach ($lines as $line) {
            $sub = $line['jumlah'] * $line['harga'];
            $total += $sub;
            PembelianDetail::create([
                'pembelian_id' => $po->id,
                'barang_id' => $line['barang']->id,
                'jumlah' => $line['jumlah'],
                'harga_satuan' => $line['harga'],
                'subtotal' => $sub,
            ]);
        }

        return $total;
    }

    private function seedPengeluaran(User $admin): void
    {
        Pengeluaran::firstOrCreate(
            ['keterangan' => '[DEMO] Biaya kirim & konsumsi kantor'],
            [
                'tanggal' => now()->subDays(2)->toDateString(),
                'kategori' => 'Operasional',
                'nominal' => 450000,
                'dibuat_oleh' => $admin->id,
            ]
        );

        Pengeluaran::firstOrCreate(
            ['keterangan' => '[DEMO] Perawatan kendaraan distribusi'],
            [
                'tanggal' => now()->subDays(8)->toDateString(),
                'kategori' => 'Transport',
                'nominal' => 1250000,
                'dibuat_oleh' => $admin->id,
            ]
        );
    }

    private function seedPermintaanDanSuratJalan(User $sales, User $pelanggan, array $b): void
    {
        $pr1 = PermintaanBarang::firstOrCreate(
            ['no_permintaan' => 'SEED-PR-0001'],
            [
                'pelanggan_id' => $pelanggan->id,
                'diproses_oleh' => null,
                'tanggal_request' => now()->subDay()->toDateString(),
                'tanggal_dibutuhkan' => now()->addDays(3)->toDateString(),
                'status' => 'pending',
                'catatan' => 'Permintaan baru menunggu sales (demo).',
            ]
        );
        if ($pr1->detail()->count() === 0) {
            $this->attachPermintaanDetail($pr1, $b[0], 15, 0, 0, false);
            $this->attachPermintaanDetail($pr1, $b[1], 8, 0, 0, false);
        }

        $pr2 = PermintaanBarang::firstOrCreate(
            ['no_permintaan' => 'SEED-PR-0002'],
            [
                'pelanggan_id' => $pelanggan->id,
                'diproses_oleh' => $sales->id,
                'tanggal_request' => now()->subDays(3)->toDateString(),
                'tanggal_dibutuhkan' => now()->addDay()->toDateString(),
                'status' => 'diproses',
                'catatan' => 'Sudah dicek sales — siap buat surat jalan.',
            ]
        );
        if ($pr2->detail()->count() === 0) {
            $hj0 = (float) $b[0]->harga_jual;
            $hj1 = (float) $b[1]->harga_jual;
            $this->attachPermintaanDetail($pr2, $b[0], 20, 18, $hj0, true);
            $this->attachPermintaanDetail($pr2, $b[1], 10, 10, $hj1, true);
        }

        $pr3 = PermintaanBarang::firstOrCreate(
            ['no_permintaan' => 'SEED-PR-0003'],
            [
                'pelanggan_id' => $pelanggan->id,
                'diproses_oleh' => $sales->id,
                'tanggal_request' => now()->subDays(5)->toDateString(),
                'tanggal_dibutuhkan' => now()->subDay()->toDateString(),
                'status' => 'siap_kirim',
                'catatan' => 'Surat jalan sudah dibuat (data demo).',
            ]
        );
        if ($pr3->detail()->count() === 0) {
            $hj0 = (float) $b[0]->harga_jual;
            $this->attachPermintaanDetail($pr3, $b[0], 12, 12, $hj0, true);
            $hj2 = (float) $b[2]->harga_jual;
            $this->attachPermintaanDetail($pr3, $b[2], 6, 5, $hj2, true);
        }

        $sj = SuratJalan::firstOrCreate(
            ['no_sj' => 'SEED-SJ-0001'],
            [
                'permintaan_id' => $pr3->id,
                'dibuat_oleh' => $sales->id,
                'tanggal' => now()->subDay()->toDateString(),
                'driver' => 'Budi Santoso',
                'no_kendaraan' => 'B 1234 DEMO',
                'alamat_tujuan' => 'Jl. Contoh No. 99, Jakarta',
                'status' => 'dibuat',
                'catatan' => 'Pengiriman contoh dari seeder.',
            ]
        );
        if ($sj->detail()->count() === 0) {
            foreach ($pr3->detail as $d) {
                if (! $d->is_checked || $d->jumlah_disetujui <= 0) {
                    continue;
                }
                SuratJalanDetail::create([
                    'surat_jalan_id' => $sj->id,
                    'barang_id' => $d->barang_id,
                    'jumlah' => $d->jumlah_disetujui,
                ]);
            }
        }

        // Samakan stok dengan skenario SJ (seperti alur buatSuratJalan)
        if (StokMutasi::where('referensi', 'SEED-SJ-0001')->doesntExist()) {
            foreach ($pr3->detail as $d) {
                if (! $d->is_checked || $d->jumlah_disetujui <= 0) {
                    continue;
                }
                $stok = Stok::where('barang_id', $d->barang_id)->first();
                if ($stok && $stok->jumlah >= $d->jumlah_disetujui) {
                    $stok->decrement('jumlah', $d->jumlah_disetujui);
                    StokMutasi::create([
                        'barang_id' => $d->barang_id,
                        'tipe' => 'keluar',
                        'jumlah' => $d->jumlah_disetujui,
                        'harga_satuan' => $stok->harga_rata,
                        'referensi' => 'SEED-SJ-0001',
                        'keterangan' => 'Demo seeder — surat jalan SEED-SJ-0001',
                        'user_id' => $sales->id,
                    ]);
                }
            }
        }
    }

    private function attachPermintaanDetail(
        PermintaanBarang $permintaan,
        Barang $barang,
        float $diminta,
        float $disetujui,
        float $hargaJual,
        bool $checked
    ): void {
        $jSet = $disetujui > 0 ? $disetujui : $diminta;
        PermintaanDetail::create([
            'permintaan_id' => $permintaan->id,
            'barang_id' => $barang->id,
            'jumlah_diminta' => $diminta,
            'jumlah_disetujui' => $jSet,
            'harga_jual' => $hargaJual,
            'subtotal_jual' => $jSet * $hargaJual,
            'is_checked' => $checked,
        ]);
    }
}
