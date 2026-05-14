<?php

namespace App\Http\Controllers\Sawit;

use App\Http\Controllers\Controller;
use App\Models\SawitBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = SawitBarang::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_sawit', 'like', "%{$search}%")
                  ->orWhere('nama_sawit', 'like', "%{$search}%");
            });
        }

        // Filter aktif/non-aktif
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $barang = $query->latest()->paginate(20);

        return view('sawit.admin.barang.index', compact('barang'));
    }

    public function create()
    {
        // Generate ID Sawit otomatis
        $lastBarang = SawitBarang::orderBy('id', 'desc')->first();
        $nextNumber = $lastBarang ? ((int) substr($lastBarang->id_sawit, 4)) + 1 : 1;
        $autoIdSawit = 'SWT-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return view('sawit.admin.barang.create', compact('autoIdSawit'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sawit' => 'required|string|max:50|unique:sawit_barang,id_sawit',
            'nama_sawit' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'id_sawit.required' => 'ID Sawit wajib diisi',
            'id_sawit.unique' => 'ID Sawit sudah digunakan',
            'nama_sawit.required' => 'Nama Sawit wajib diisi',
        ]);

        $validated['is_active'] = $request->has('is_active');

        SawitBarang::create($validated);

        return redirect()->route('sawit.admin.barang.index')
            ->with('success', 'Data barang sawit berhasil ditambahkan');
    }

    public function show(SawitBarang $barang)
    {
        $barang->load(['penjualan' => function($q) {
            $q->where('status', 'done')->latest();
        }, 'pembelian' => function($q) {
            $q->where('status', 'done')->latest();
        }]);

        // Statistik
        $totalPembelian = $barang->pembelian()->where('status', 'done')->sum('qty_setelah_refaksi');
        $totalPenjualan = $barang->penjualan()->where('status', 'done')->sum('total_kg_setelah_refaksi');
        $nilaiPembelian = $barang->pembelian()->where('status', 'done')->sum('total_harga');
        $nilaiPenjualan = $barang->penjualan()->where('status', 'done')->sum('total_harga');

        return view('sawit.admin.barang.show', compact(
            'barang',
            'totalPembelian',
            'totalPenjualan',
            'nilaiPembelian',
            'nilaiPenjualan'
        ));
    }

    public function edit(SawitBarang $barang)
    {
        return view('sawit.admin.barang.edit', compact('barang'));
    }

    public function update(Request $request, SawitBarang $barang)
    {
        $validated = $request->validate([
            'id_sawit' => 'required|string|max:50|unique:sawit_barang,id_sawit,' . $barang->id,
            'nama_sawit' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'id_sawit.required' => 'ID Sawit wajib diisi',
            'id_sawit.unique' => 'ID Sawit sudah digunakan',
            'nama_sawit.required' => 'Nama Sawit wajib diisi',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $barang->update($validated);

        return redirect()->route('sawit.admin.barang.index')
            ->with('success', 'Data barang sawit berhasil diperbarui');
    }

    public function destroy(SawitBarang $barang)
    {
        // Cek apakah barang sudah digunakan dalam transaksi
        $hasTransactions = $barang->penjualan()->exists() || $barang->pembelian()->exists();

        if ($hasTransactions) {
            return redirect()->route('sawit.admin.barang.index')
                ->with('error', 'Barang tidak dapat dihapus karena sudah digunakan dalam transaksi');
        }

        $barang->delete();

        return redirect()->route('sawit.admin.barang.index')
            ->with('success', 'Data barang sawit berhasil dihapus');
    }

    public function export(Request $request)
    {
        $query = SawitBarang::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_sawit', 'like', "%{$search}%")
                  ->orWhere('nama_sawit', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $barang = $query->latest()->get();

        // Export ke Excel menggunakan library yang sama dengan sistem lama
        $filename = 'data-barang-sawit-' . date('Y-m-d-His') . '.xlsx';
        
        return response()->streamDownload(function() use ($barang) {
            echo $this->generateExcel($barang);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function generateExcel($barang)
    {
        $html = '<table border="1">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>No</th>';
        $html .= '<th>ID Sawit</th>';
        $html .= '<th>Nama Sawit</th>';
        $html .= '<th>Total Kg</th>';
        $html .= '<th>Status</th>';
        $html .= '<th>Keterangan</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        foreach ($barang as $index => $item) {
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . $item->id_sawit . '</td>';
            $html .= '<td>' . $item->nama_sawit . '</td>';
            $html .= '<td>' . number_format($item->total_kg, 2) . '</td>';
            $html .= '<td>' . ($item->is_active ? 'Aktif' : 'Tidak Aktif') . '</td>';
            $html .= '<td>' . ($item->keterangan ?? '-') . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        return $html;
    }
}
