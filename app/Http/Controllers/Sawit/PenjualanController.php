<?php

namespace App\Http\Controllers\Sawit;

use App\Http\Controllers\Controller;
use App\Models\SawitPenjualan;
use App\Models\SawitBarang;
use App\Models\SawitPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = SawitPenjualan::with(['perusahaan', 'barang']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_invoice', 'like', "%{$search}%")
                  ->orWhereHas('perusahaan', function($q2) use ($search) {
                      $q2->where('nama_perusahaan', 'like', "%{$search}%");
                  });
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

        // Filter perusahaan
        if ($request->filled('perusahaan_id')) {
            $query->where('perusahaan_id', $request->perusahaan_id);
        }

        // Filter barang
        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $penjualan = $query->latest('tanggal')->paginate(20);
        $perusahaan = SawitPerusahaan::active()->orderBy('nama_perusahaan')->get();
        $barang = SawitBarang::active()->orderBy('nama_sawit')->get();

        return view('sawit.admin.penjualan.index', compact('penjualan', 'perusahaan', 'barang'));
    }

    public function create()
    {
        $noInvoice = SawitPenjualan::generateNoInvoice();
        $perusahaan = SawitPerusahaan::active()->orderBy('nama_perusahaan')->get();
        $barang = SawitBarang::active()->orderBy('nama_sawit')->get();

        return view('sawit.admin.penjualan.create', compact('noInvoice', 'perusahaan', 'barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_invoice' => 'required|string|unique:sawit_penjualan,no_invoice',
            'tanggal' => 'required|date',
            'perusahaan_id' => 'required|exists:sawit_perusahaan,id',
            'barang_id' => 'required|exists:sawit_barang,id',
            'qty_timbangan' => 'required|numeric|min:0',
            'refaksi' => 'nullable|numeric|min:0',
            'harga_per_kg' => 'required|numeric|min:0',
            'status' => 'required|in:draft,done',
            'tanggal_bayar' => 'nullable|date',
            'pembayaran_invoice' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
            'jenis_kendaraan' => 'nullable|string|max:100',
            'no_mobil' => 'nullable|string|max:50',
            'nama_supir' => 'nullable|string|max:100',
            'keterangan_potongan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ], [
            'no_invoice.required' => 'Nomor invoice wajib diisi',
            'no_invoice.unique' => 'Nomor invoice sudah digunakan',
            'tanggal.required' => 'Tanggal wajib diisi',
            'perusahaan_id.required' => 'Perusahaan wajib dipilih',
            'barang_id.required' => 'Jenis sawit wajib dipilih',
            'qty_timbangan.required' => 'QTY Timbangan wajib diisi',
            'harga_per_kg.required' => 'Harga per kg wajib diisi',
            'status.required' => 'Status wajib dipilih',
        ]);

        $validated['refaksi'] = $validated['refaksi'] ?? 0;
        $validated['pembayaran_invoice'] = $validated['pembayaran_invoice'] ?? 0;
        $validated['potongan'] = $validated['potongan'] ?? 0;
        $validated['created_by'] = Auth::id();

        SawitPenjualan::create($validated);

        return redirect()->route('sawit.admin.penjualan.index')
            ->with('success', 'Data penjualan berhasil ditambahkan');
    }

    public function show(SawitPenjualan $penjualan)
    {
        $penjualan->load(['perusahaan', 'barang', 'suratJalan', 'creator', 'updater', 'paymentHistory']);

        return view('sawit.admin.penjualan.show', compact('penjualan'));
    }

    public function edit(SawitPenjualan $penjualan)
    {
        $perusahaan = SawitPerusahaan::active()->orderBy('nama_perusahaan')->get();
        $barang = SawitBarang::active()->orderBy('nama_sawit')->get();

        return view('sawit.admin.penjualan.edit', compact('penjualan', 'perusahaan', 'barang'));
    }

    public function update(Request $request, SawitPenjualan $penjualan)
    {
        $validated = $request->validate([
            'no_invoice' => 'required|string|unique:sawit_penjualan,no_invoice,' . $penjualan->id,
            'tanggal' => 'required|date',
            'perusahaan_id' => 'required|exists:sawit_perusahaan,id',
            'barang_id' => 'required|exists:sawit_barang,id',
            'qty_timbangan' => 'required|numeric|min:0',
            'refaksi' => 'nullable|numeric|min:0',
            'harga_per_kg' => 'required|numeric|min:0',
            'status' => 'required|in:draft,done',
            'tanggal_bayar' => 'nullable|date',
            'pembayaran_invoice' => 'nullable|numeric|min:0',
            'potongan' => 'nullable|numeric|min:0',
            'jenis_kendaraan' => 'nullable|string|max:100',
            'no_mobil' => 'nullable|string|max:50',
            'nama_supir' => 'nullable|string|max:100',
            'keterangan_potongan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $validated['refaksi'] = $validated['refaksi'] ?? 0;
        $validated['pembayaran_invoice'] = $validated['pembayaran_invoice'] ?? 0;
        $validated['potongan'] = $validated['potongan'] ?? 0;
        $validated['updated_by'] = Auth::id();

        $penjualan->update($validated);

        return redirect()->route('sawit.admin.penjualan.index')
            ->with('success', 'Data penjualan berhasil diperbarui');
    }

    public function destroy(SawitPenjualan $penjualan)
    {
        // Cek apakah sudah ada surat jalan
        if ($penjualan->suratJalan) {
            return redirect()->route('sawit.admin.penjualan.index')
                ->with('error', 'Penjualan tidak dapat dihapus karena sudah memiliki surat jalan');
        }

        $penjualan->delete();

        return redirect()->route('sawit.admin.penjualan.index')
            ->with('success', 'Data penjualan berhasil dihapus');
    }

    public function print(SawitPenjualan $penjualan)
    {
        $penjualan->load(['perusahaan', 'barang']);

        return view('sawit.admin.penjualan.print', compact('penjualan'));
    }

    public function export(Request $request)
    {
        $query = SawitPenjualan::with(['perusahaan', 'barang']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('perusahaan_id')) {
            $query->where('perusahaan_id', $request->perusahaan_id);
        }

        if ($request->filled('barang_id')) {
            $query->where('barang_id', $request->barang_id);
        }

        $penjualan = $query->latest('tanggal')->get();

        $filename = 'penjualan-sawit-' . date('Y-m-d-His') . '.xlsx';
        
        return response()->streamDownload(function() use ($penjualan) {
            echo $this->generateExcel($penjualan);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function generateExcel($penjualan)
    {
        $html = '<table border="1">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>No</th>';
        $html .= '<th>No Invoice</th>';
        $html .= '<th>Tanggal</th>';
        $html .= '<th>Perusahaan</th>';
        $html .= '<th>Jenis Sawit</th>';
        $html .= '<th>QTY Timbangan</th>';
        $html .= '<th>Refaksi</th>';
        $html .= '<th>Total Kg</th>';
        $html .= '<th>Harga/Kg</th>';
        $html .= '<th>Total Harga</th>';
        $html .= '<th>Pembayaran</th>';
        $html .= '<th>Potongan</th>';
        $html .= '<th>Selisih</th>';
        $html .= '<th>Status</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        foreach ($penjualan as $index => $item) {
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            $html .= '<td>' . $item->no_invoice . '</td>';
            $html .= '<td>' . $item->tanggal->format('d/m/Y') . '</td>';
            $html .= '<td>' . $item->perusahaan->nama_perusahaan . '</td>';
            $html .= '<td>' . $item->barang->nama_sawit . '</td>';
            $html .= '<td>' . number_format($item->qty_timbangan, 2) . '</td>';
            $html .= '<td>' . number_format($item->refaksi, 2) . '</td>';
            $html .= '<td>' . number_format($item->total_kg_setelah_refaksi, 2) . '</td>';
            $html .= '<td>' . number_format($item->harga_per_kg, 0) . '</td>';
            $html .= '<td>' . number_format($item->total_harga, 0) . '</td>';
            $html .= '<td>' . number_format($item->pembayaran_invoice, 0) . '</td>';
            $html .= '<td>' . number_format($item->potongan, 0) . '</td>';
            $html .= '<td>' . number_format($item->selisih_pembayaran, 0) . '</td>';
            $html .= '<td>' . strtoupper($item->status) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        
        return $html;
    }
}
