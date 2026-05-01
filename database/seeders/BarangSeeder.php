<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Stok;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Sayuran
            ['kode' => 'BRG-001', 'nama' => 'Bayam Segar',       'kategori' => 'Sayuran',        'satuan' => 'kg',      'harga_jual' => 8000,   'stok_minimum' => 10],
            ['kode' => 'BRG-002', 'nama' => 'Kangkung',          'kategori' => 'Sayuran',        'satuan' => 'kg',      'harga_jual' => 6000,   'stok_minimum' => 10],
            ['kode' => 'BRG-003', 'nama' => 'Wortel',            'kategori' => 'Sayuran',        'satuan' => 'kg',      'harga_jual' => 12000,  'stok_minimum' => 15],
            ['kode' => 'BRG-004', 'nama' => 'Kentang',           'kategori' => 'Sayuran',        'satuan' => 'kg',      'harga_jual' => 15000,  'stok_minimum' => 20],
            ['kode' => 'BRG-005', 'nama' => 'Tomat',             'kategori' => 'Sayuran',        'satuan' => 'kg',      'harga_jual' => 10000,  'stok_minimum' => 10],
            ['kode' => 'BRG-006', 'nama' => 'Brokoli',           'kategori' => 'Sayuran',        'satuan' => 'kg',      'harga_jual' => 25000,  'stok_minimum' => 5],

            // Buah
            ['kode' => 'BRG-007', 'nama' => 'Pisang Cavendish',  'kategori' => 'Buah-buahan',   'satuan' => 'kg',      'harga_jual' => 20000,  'stok_minimum' => 10],
            ['kode' => 'BRG-008', 'nama' => 'Jeruk Nipis',       'kategori' => 'Buah-buahan',   'satuan' => 'kg',      'harga_jual' => 18000,  'stok_minimum' => 5],
            ['kode' => 'BRG-009', 'nama' => 'Mangga Harum Manis','kategori' => 'Buah-buahan',   'satuan' => 'kg',      'harga_jual' => 35000,  'stok_minimum' => 5],

            // Daging & Ikan
            ['kode' => 'BRG-010', 'nama' => 'Ayam Potong',       'kategori' => 'Daging & Ikan', 'satuan' => 'kg',      'harga_jual' => 38000,  'stok_minimum' => 20],
            ['kode' => 'BRG-011', 'nama' => 'Daging Sapi',       'kategori' => 'Daging & Ikan', 'satuan' => 'kg',      'harga_jual' => 120000, 'stok_minimum' => 10],
            ['kode' => 'BRG-012', 'nama' => 'Ikan Bandeng',      'kategori' => 'Daging & Ikan', 'satuan' => 'kg',      'harga_jual' => 30000,  'stok_minimum' => 10],
            ['kode' => 'BRG-013', 'nama' => 'Udang Segar',       'kategori' => 'Daging & Ikan', 'satuan' => 'kg',      'harga_jual' => 75000,  'stok_minimum' => 5],

            // Bumbu & Rempah
            ['kode' => 'BRG-014', 'nama' => 'Bawang Merah',      'kategori' => 'Bumbu & Rempah','satuan' => 'kg',      'harga_jual' => 35000,  'stok_minimum' => 5],
            ['kode' => 'BRG-015', 'nama' => 'Bawang Putih',      'kategori' => 'Bumbu & Rempah','satuan' => 'kg',      'harga_jual' => 40000,  'stok_minimum' => 5],
            ['kode' => 'BRG-016', 'nama' => 'Cabai Merah',       'kategori' => 'Bumbu & Rempah','satuan' => 'kg',      'harga_jual' => 45000,  'stok_minimum' => 3],
            ['kode' => 'BRG-017', 'nama' => 'Jahe',              'kategori' => 'Bumbu & Rempah','satuan' => 'kg',      'harga_jual' => 20000,  'stok_minimum' => 3],
            ['kode' => 'BRG-018', 'nama' => 'Kunyit',            'kategori' => 'Bumbu & Rempah','satuan' => 'kg',      'harga_jual' => 15000,  'stok_minimum' => 2],

            // Beras & Serealia
            ['kode' => 'BRG-019', 'nama' => 'Beras Premium 5kg', 'kategori' => 'Beras & Serealia','satuan' => 'karton','harga_jual' => 75000,  'stok_minimum' => 30],
            ['kode' => 'BRG-020', 'nama' => 'Tepung Terigu',     'kategori' => 'Beras & Serealia','satuan' => 'kg',    'harga_jual' => 12000,  'stok_minimum' => 20],

            // Minyak & Lemak
            ['kode' => 'BRG-021', 'nama' => 'Minyak Goreng 2L',  'kategori' => 'Minyak & Lemak', 'satuan' => 'botol', 'harga_jual' => 38000,  'stok_minimum' => 20],
            ['kode' => 'BRG-022', 'nama' => 'Margarin 200gr',    'kategori' => 'Minyak & Lemak', 'satuan' => 'pcs',   'harga_jual' => 9000,   'stok_minimum' => 10],

            // Susu & Telur
            ['kode' => 'BRG-023', 'nama' => 'Telur Ayam',        'kategori' => 'Susu & Telur',  'satuan' => 'kg',     'harga_jual' => 28000,  'stok_minimum' => 20],
            ['kode' => 'BRG-024', 'nama' => 'Susu Segar 1L',     'kategori' => 'Susu & Telur',  'satuan' => 'liter',  'harga_jual' => 18000,  'stok_minimum' => 10],
        ];

        foreach ($items as $item) {
            $barang = Barang::create(array_merge($item, ['deskripsi' => null, 'is_active' => true]));
            Stok::create([
                'barang_id'  => $barang->id,
                'jumlah'     => rand(5, 100),
                'harga_rata' => $item['harga_jual'] * 0.75,
            ]);
        }
    }
}
