<?php

namespace App\Http\Controllers\Sawit;

use App\Http\Controllers\Controller;
use App\Models\SawitPerusahaan;
use Illuminate\Http\Request;

class PerusahaanController extends Controller
{
    public function index(Request $request)
    {
        $query = SawitPerusahaan::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%");
            });
        }

        $perusahaan = $query->latest()->paginate(20);

        return view('sawit.admin.perusahaan.index', compact('perusahaan'));
    }

    public function create()
    {
        return view('sawit.admin.perusahaan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        SawitPerusahaan::create($validated);

        return redirect()->route('sawit.admin.perusahaan.index')
            ->with('success', 'Data perusahaan berhasil ditambahkan');
    }

    public function edit(SawitPerusahaan $perusahaan)
    {
        return view('sawit.admin.perusahaan.edit', compact('perusahaan'));
    }

    public function update(Request $request, SawitPerusahaan $perusahaan)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $perusahaan->update($validated);

        return redirect()->route('sawit.admin.perusahaan.index')
            ->with('success', 'Data perusahaan berhasil diperbarui');
    }

    public function destroy(SawitPerusahaan $perusahaan)
    {
        if ($perusahaan->penjualan()->exists()) {
            return redirect()->route('sawit.admin.perusahaan.index')
                ->with('error', 'Perusahaan tidak dapat dihapus karena sudah digunakan dalam transaksi');
        }

        $perusahaan->delete();

        return redirect()->route('sawit.admin.perusahaan.index')
            ->with('success', 'Data perusahaan berhasil dihapus');
    }
}
