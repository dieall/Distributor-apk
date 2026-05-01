<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Stok;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('stok')->latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode', 'like', "%{$request->search}%")
                  ->orWhere('nama', 'like', "%{$request->search}%")
                  ->orWhere('kategori', 'like', "%{$request->search}%");
            });
        }

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        $barang = $query->paginate(15)->withQueryString();
        $kategoris = Barang::distinct()->pluck('kategori');

        return view('admin.barang.index', compact('barang', 'kategoris'));
    }

    public function create()
    {
        return view('admin.barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'          => 'required|string|max:20|unique:barang',
            'nama'          => 'required|string|max:255',
            'kategori'      => 'required|string|max:100',
            'satuan'        => 'required|string|max:20',
            'harga_jual'    => 'required|numeric|min:0',
            'stok_minimum'  => 'required|integer|min:0',
            'deskripsi'     => 'nullable|string',
        ]);

        $barang = Barang::create($validated);
        Stok::create(['barang_id' => $barang->id, 'jumlah' => 0, 'harga_rata' => 0]);

        return redirect()->route('admin.barang.index')
            ->with('success', "Barang <strong>{$barang->nama}</strong> berhasil ditambahkan.");
    }

    public function show(Barang $barang)
    {
        $barang->load('stok');
        $mutasi = $barang->stokMutasi()->with('user')->latest()->paginate(15);
        return view('admin.barang.show', compact('barang', 'mutasi'));
    }

    public function edit(Barang $barang)
    {
        return view('admin.barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kode'         => 'required|string|max:20|unique:barang,kode,' . $barang->id,
            'nama'         => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'satuan'       => 'required|string|max:20',
            'harga_jual'   => 'required|numeric|min:0',
            'stok_minimum' => 'required|integer|min:0',
            'deskripsi'    => 'nullable|string',
            'is_active'    => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $barang->update($validated);

        return redirect()->route('admin.barang.index')
            ->with('success', "Barang <strong>{$barang->nama}</strong> berhasil diperbarui.");
    }

    public function destroy(Barang $barang)
    {
        $nama = $barang->nama;
        $barang->delete();

        return redirect()->route('admin.barang.index')
            ->with('success', "Barang <strong>{$nama}</strong> berhasil dihapus.");
    }
}
