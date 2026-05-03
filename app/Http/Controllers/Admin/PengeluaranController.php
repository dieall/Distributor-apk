<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengeluaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengeluaran::with('pembuat')->latest('tanggal')->latest();

        if ($request->filled('bulan')) {
            [$year, $month] = explode('-', $request->bulan);
            $query->whereYear('tanggal', $year)->whereMonth('tanggal', $month);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kategori', 'like', "%{$request->search}%")
                  ->orWhere('keterangan', 'like', "%{$request->search}%");
            });
        }

        $pengeluaran = $query->paginate(15)->withQueryString();
        $total = (clone $query)->sum('nominal');

        return view('admin.pengeluaran.index', compact('pengeluaran', 'total'));
    }

    public function show(Pengeluaran $pengeluaran)
    {
        $pengeluaran->load('pembuat');

        return view('admin.pengeluaran.show', compact('pengeluaran'));
    }

    public function create()
    {
        return view('admin.pengeluaran.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string|max:80',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'bukti_foto' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti-pengeluaran', 'public');
        }

        $data['dibuat_oleh'] = auth()->id();
        $pengeluaran = Pengeluaran::create($data);

        return redirect()->route('admin.pengeluaran.show', $pengeluaran)->with('success', 'Pengeluaran berhasil disimpan.');
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        if ($pengeluaran->bukti_foto) {
            Storage::disk('public')->delete($pengeluaran->bukti_foto);
        }
        $pengeluaran->delete();

        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
