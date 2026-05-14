<?php

namespace App\Http\Controllers\Sawit;

use App\Http\Controllers\Controller;
use App\Models\SawitPenjual;
use Illuminate\Http\Request;

class PenjualController extends Controller
{
    public function index(Request $request)
    {
        $query = SawitPenjual::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_penjual', 'like', "%{$search}%");
        }

        $penjual = $query->latest()->paginate(20);

        return view('sawit.admin.penjual.index', compact('penjual'));
    }

    public function create()
    {
        return view('sawit.admin.penjual.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penjual' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        SawitPenjual::create($validated);

        return redirect()->route('sawit.admin.penjual.index')
            ->with('success', 'Data penjual berhasil ditambahkan');
    }

    public function edit(SawitPenjual $penjual)
    {
        return view('sawit.admin.penjual.edit', compact('penjual'));
    }

    public function update(Request $request, SawitPenjual $penjual)
    {
        $validated = $request->validate([
            'nama_penjual' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:50',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $penjual->update($validated);

        return redirect()->route('sawit.admin.penjual.index')
            ->with('success', 'Data penjual berhasil diperbarui');
    }

    public function destroy(SawitPenjual $penjual)
    {
        if ($penjual->pembelian()->exists()) {
            return redirect()->route('sawit.admin.penjual.index')
                ->with('error', 'Penjual tidak dapat dihapus karena sudah digunakan dalam transaksi');
        }

        $penjual->delete();

        return redirect()->route('sawit.admin.penjual.index')
            ->with('success', 'Data penjual berhasil dihapus');
    }
}
