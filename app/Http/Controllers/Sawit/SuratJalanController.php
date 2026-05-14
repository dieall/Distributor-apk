<?php

namespace App\Http\Controllers\Sawit;

use App\Http\Controllers\Controller;
use App\Models\SawitSuratJalan;
use App\Models\SawitPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratJalanController extends Controller
{
    public function index(Request $request)
    {
        $query = SawitSuratJalan::with(['penjualan.perusahaan', 'penjualan.barang']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_surat_jalan', 'like', "%{$search}%")
                  ->orWhere('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('no_mobil', 'like', "%{$search}%");
            });
        }

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $suratJalan = $query->latest('tanggal')->paginate(20);

        return view('sawit.admin.surat-jalan.index', compact('suratJalan'));
    }

    public function create(Request $request)
    {
        $noSuratJalan = SawitSuratJalan::generateNoSuratJalan();
        
        // Ambil penjualan yang belum punya surat jalan
        $penjualan = SawitPenjualan::whereDoesntHave('suratJalan')
            ->where('status', 'done')
            ->with(['perusahaan', 'barang'])
            ->latest()
            ->get();

        // Jika ada penjualan_id dari parameter
        $selectedPenjualan = null;
        if ($request->filled('penjualan_id')) {
            $selectedPenjualan = SawitPenjualan::with(['perusahaan', 'barang'])
                ->find($request->penjualan_id);
        }

        return view('sawit.admin.surat-jalan.create', compact('noSuratJalan', 'penjualan', 'selectedPenjualan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat_jalan' => 'required|string|unique:sawit_surat_jalan,no_surat_jalan',
            'penjualan_id' => 'required|exists:sawit_penjualan,id',
            'tanggal' => 'required|date',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_tujuan' => 'required|string',
            'jenis_sawit' => 'required|string|max:255',
            'total_kg' => 'required|numeric|min:0',
            'jenis_kendaraan' => 'required|string|max:100',
            'no_mobil' => 'required|string|max:50',
            'nama_supir' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ], [
            'no_surat_jalan.required' => 'Nomor surat jalan wajib diisi',
            'no_surat_jalan.unique' => 'Nomor surat jalan sudah digunakan',
            'penjualan_id.required' => 'Penjualan wajib dipilih',
            'tanggal.required' => 'Tanggal wajib diisi',
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi',
            'alamat_tujuan.required' => 'Alamat tujuan wajib diisi',
            'jenis_sawit.required' => 'Jenis sawit wajib diisi',
            'total_kg.required' => 'Total kg wajib diisi',
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib diisi',
            'no_mobil.required' => 'No mobil wajib diisi',
            'nama_supir.required' => 'Nama supir wajib diisi',
        ]);

        // Cek apakah penjualan sudah punya surat jalan
        $existingSJ = SawitSuratJalan::where('penjualan_id', $validated['penjualan_id'])->first();
        if ($existingSJ) {
            return back()->withErrors(['penjualan_id' => 'Penjualan ini sudah memiliki surat jalan'])
                ->withInput();
        }

        $validated['created_by'] = Auth::id();

        SawitSuratJalan::create($validated);

        return redirect()->route('sawit.admin.surat-jalan.index')
            ->with('success', 'Surat jalan berhasil dibuat');
    }

    public function show(SawitSuratJalan $suratJalan)
    {
        $suratJalan->load(['penjualan.perusahaan', 'penjualan.barang', 'creator']);

        return view('sawit.admin.surat-jalan.show', compact('suratJalan'));
    }

    public function print(SawitSuratJalan $suratJalan)
    {
        $suratJalan->load(['penjualan.perusahaan', 'penjualan.barang']);

        return view('sawit.admin.surat-jalan.print', compact('suratJalan'));
    }

    public function destroy(SawitSuratJalan $suratJalan)
    {
        $suratJalan->delete();

        return redirect()->route('sawit.admin.surat-jalan.index')
            ->with('success', 'Surat jalan berhasil dihapus');
    }

    // API untuk get data penjualan
    public function getPenjualan($id)
    {
        $penjualan = SawitPenjualan::with(['perusahaan', 'barang'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'no_invoice' => $penjualan->no_invoice,
                'nama_perusahaan' => $penjualan->perusahaan->nama_perusahaan,
                'alamat' => $penjualan->perusahaan->alamat,
                'jenis_sawit' => $penjualan->barang->nama_sawit,
                'total_kg' => $penjualan->total_kg_setelah_refaksi,
                'jenis_kendaraan' => $penjualan->jenis_kendaraan,
                'no_mobil' => $penjualan->no_mobil,
                'nama_supir' => $penjualan->nama_supir,
            ]
        ]);
    }
}
