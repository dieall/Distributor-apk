<?php

namespace App\Http\Controllers\Sawit;

use App\Http\Controllers\Controller;
use App\Models\SawitPembelian;
use App\Models\SawitBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = SawitPembelian::with(['barang']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_pembelian', 'like', "%{$search}%")
                  ->orWhere('nama_penjual', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter barang
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $pembelian = $query->latest('tanggal')->paginate(20);
        $barang = SawitBarang::active()->orderBy('nama_sawit')->get();

        return view('sawit.admin.pembelian.index', compact('pembelian', 'barang'));
    }

    public function create()
    {
        $noPembelian = SawitPembelian::generateNoPembelian();
        $barang = SawitBarang::active()->orderBy('nama_sawit')->get();

        return view('sawit.admin.pembelian.create', compact('noPembelian', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_pembelian' => 'required|string|unique:sawit_pembelian,no_pembelian',
            'tanggal' => 'required|date',
            'barang_id' => 'required|exists:sawit_barang,id',
            'nama_penjual' => 'required|string|max:255',
            'qty_timbangan' => 'required|numeric|min:0',
            'refaksi' => 'nullable|numeric|min:0',
            'harga_per_kg' => 'required|numeric|min:0',
            'potongan_dp' => 'nullable|numeric|min:0',
            'dp_sebelumnya' => 'nullable|numeric|min:0',
            'tanggal_transfer' => 'nullable|date',
            'status' => 'required|in:draft,done',
            'type_payment' => 'required|in:cash,transfer,tempo',
            'keterangan' => 'nullable|string',
        ], [
            'no_pembelian.required' => 'Nomor pembelian wajib diisi',
            'no_pembelian.unique' => 'Nomor pembelian sudah digunakan',
            'tanggal.required' => 'Tanggal wajib diisi',
            'barang_id.required' => 'Jenis sawit wajib dipilih',
            'nama_penjual.required' => 'Nama pembeli/penjual wajib diisi',
            'qty_timbangan.required' => 'QTY Timbangan wajib diisi',
            'harga_per_kg.required' => 'Harga per kg wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'type_payment.required' => 'Type payment wajib dipilih',
        ]);

        $validated['refaksi'] = $validated['refaksi'] ?? 0;
        $validated['potongan_dp'] = $validated['potongan_dp'] ?? 0;
        $validated['dp_sebelumnya'] = $validated['dp_sebelumnya'] ?? 0;
        $validated['created_by'] = Auth::id();

        SawitPembelian::create($validated);

        return redirect()->route('sawit.admin.pembelian.index')
            ->with('success', 'Data pembelian berhasil ditambahkan');
    }

    public function show(SawitPembelian $pembelian)
    {
        $pembelian->load(['barang', 'creator', 'updater']);

        return view('sawit.admin.pembelian.show', compact('pembelian'));
    }

    public function edit(SawitPembelian $pembelian)
    {
        $barang = SawitBarang::active()->orderBy('nama_sawit')->get();

        return view('sawit.admin.pembelian.edit', compact('pembelian', 'barang'));
    }

    public function update(Request $request, SawitPembelian $pembelian)
    {
        $validated = $request->validate([
            'no_pembelian' => 'required|string|unique:sawit_pembelian,no_pembelian,' . $pembelian->id,
            'tanggal' => 'required|date',
            'barang_id' => 'required|exists:sawit_barang,id',
            'nama_penjual' => 'required|string|max:255',
            'qty_timbangan' => 'required|numeric|min:0',
            'refaksi' => 'nullable|numeric|min:0',
            'harga_per_kg' => 'required|numeric|min:0',
            'potongan_dp' => 'nullable|numeric|min:0',
            'dp_sebelumnya' => 'nullable|numeric|min:0',
            'tanggal_transfer' => 'nullable|date',
            'status' => 'required|in:draft,done',
            'type_payment' => 'required|in:cash,transfer,tempo',
            'keterangan' => 'nullable|string',
        ]);

        $validated['refaksi'] = $validated['refaksi'] ?? 0;
        $validated['potongan_dp'] = $validated['potongan_dp'] ?? 0;
        $validated['dp_sebelumnya'] = $validated['dp_sebelumnya'] ?? 0;
        $validated['updated_by'] = Auth::id();

        $pembelian->update($validated);

        return redirect()->route('sawit.admin.pembelian.index')
            ->with('success', 'Data pembelian berhasil diperbarui');
    }

    public function destroy(SawitPembelian $pembelian)
    {
        $pembelian->delete();

        return redirect()->route('sawit.admin.pembelian.index')
            ->with('success', 'Data pembelian berhasil dihapus');
    }

    public function export(Request $request)
    {
        $query = SawitPembelian::with(['barang']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $pembelian = $query->latest('tanggal')->get();

        $filename = 'pembelian-sawit-' . date('Y-m-d-His') . '.xlsx';
        
        return response()->streamDownload(function() use ($pembelian) {
            echo $this->generateExcel($pembelian);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function generateExcel($pembelian)
    {
        $html = '<table border="1">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>No</th>';
        $html .= '<th>No Pembelian</th>';
        $html .= '<th>Tanggal</th>';
        $html .= '<th>Nama Pembeli/Penjual</th>';
        $html .= '<th>Jenis Sawit</th>';
        $html .= '<th>QTY Timbangan</th>';
        $html .= '<th>Refaksi</th>';
        $html .= '<th>QTY Setelah Refaksi</th>';
        $html .= '<th>Harga/Kg</th>';
        $html .= '<th>Total Harga</th>';
        $html .= '<th>Potongan DP</th>';
        $html .= '<th>Selisih</th>';
        $html .= '<th>Type Payment</th>';
        $html .= '<th>Status</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        foreach ($pembelian as $index => $item) {
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . $item->no_pembelian . '</td>';
            $html .= '<td>' . $item->tanggal->format('d/m/Y') . '</td>';
            $html .= '<td>' . $item->nama_penjual . '</td>';
            $html .= '<td>' . $item->barang->nama_sawit . '</td>';
            $html .= '<td>' . number_format($item->qty_timbangan, 2) . '</td>';
            $html .= '<td>' . number_format($item->refaksi, 2) . '</td>';
            $html .= '<td>' . number_format($item->qty_setelah_refaksi, 2) . '</td>';
            $html .= '<td>' . number_format($item->harga_per_kg, 0) . '</td>';
            $html .= '<td>' . number_format($item->total_harga, 0) . '</td>';
            $html .= '<td>' . number_format($item->potongan_dp, 0) . '</td>';
            $html .= '<td>' . number_format($item->selisih, 0) . '</td>';
            $html .= '<td>' . strtoupper($item->type_payment) . '</td>';
            $html .= '<td>' . strtoupper($item->status) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        return $html;
    }
}
