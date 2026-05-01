<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Stok;
use App\Models\StokMutasi;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('stok')->where('is_active', true);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('kode', 'like', "%{$request->search}%");
            });
        }

        $stok = $query->paginate(20)->withQueryString();

        $totalNilai = Stok::join('barang', 'stok.barang_id', '=', 'barang.id')
            ->where('barang.is_active', true)
            ->selectRaw('SUM(stok.jumlah * stok.harga_rata) as total')
            ->value('total') ?? 0;

        $stokRendah = Barang::with('stok')
            ->where('is_active', true)
            ->whereHas('stok', function ($q) {
                $q->whereRaw('stok.jumlah <= (SELECT stok_minimum FROM barang WHERE barang.id = stok.barang_id)');
            })
            ->count();

        $totalItem = Barang::where('is_active', true)->count();

        return view('gudang.stok.index', compact('stok', 'totalNilai', 'stokRendah', 'totalItem'));
    }

    public function show(Barang $barang)
    {
        $barang->load('stok');
        $mutasi = StokMutasi::where('barang_id', $barang->id)
            ->with('user')
            ->latest()
            ->paginate(20);
        return view('gudang.stok.show', compact('barang', 'mutasi'));
    }
}
