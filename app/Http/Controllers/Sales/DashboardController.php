<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Stok;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStokQty = (float) (Stok::query()
            ->join('barang', 'stok.barang_id', '=', 'barang.id')
            ->where('barang.is_active', true)
            ->sum('stok.jumlah'));

        $totalNilaiStok = (float) (Stok::query()
            ->join('barang', 'stok.barang_id', '=', 'barang.id')
            ->where('barang.is_active', true)
            ->selectRaw('COALESCE(SUM(stok.jumlah * stok.harga_rata), 0) as total')
            ->value('total'));

        $jumlahSkuAktif = Barang::where('is_active', true)->count();

        $barangStok = Barang::with('stok')
            ->where('is_active', true)
            ->orderBy('nama')
            ->get();

        return view('sales.dashboard', compact(
            'totalStokQty',
            'totalNilaiStok',
            'jumlahSkuAktif',
            'barangStok',
        ));
    }
}
