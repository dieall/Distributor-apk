<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PermintaanController extends Controller
{
    public function index(Request $request)
    {
        $permintaan = PermintaanBarang::where('pelanggan_id', auth()->id())
            ->with('suratJalan')
            ->latest()
            ->paginate(15);

        return view('pelanggan.permintaan.index', compact('permintaan'));
    }

    public function create()
    {
        $barang = Barang::with('stok')->where('is_active', true)->get();
        return view('pelanggan.permintaan.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_dibutuhkan' => 'nullable|date|after:today',
            'catatan'            => 'nullable|string',
            'barang_id'          => 'required|array|min:1',
            'barang_id.*'        => 'required|exists:barang,id',
            'jumlah_diminta'     => 'required|array',
            'jumlah_diminta.*'   => 'required|numeric|min:1',
        ]);

        foreach ($request->barang_id as $bid) {
            if (! isset($request->jumlah_diminta[$bid])) {
                throw ValidationException::withMessages([
                    'jumlah_diminta' => 'Jumlah untuk setiap barang yang dipilih wajib diisi.',
                ]);
            }
        }

        DB::transaction(function () use ($request) {
            $count = PermintaanBarang::whereDate('created_at', today())->count() + 1;
            $no    = 'REQ-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $permintaan = PermintaanBarang::create([
                'no_permintaan'      => $no,
                'pelanggan_id'       => auth()->id(),
                'tanggal_request'    => today(),
                'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
                'status'             => 'pending',
                'catatan'            => $request->catatan,
            ]);

            foreach ($request->barang_id as $bid) {
                $permintaan->detail()->create([
                    'barang_id'        => $bid,
                    'jumlah_diminta'   => $request->jumlah_diminta[$bid],
                    'jumlah_disetujui' => 0,
                ]);
            }
        });

        return redirect()->route('pelanggan.permintaan.index')
            ->with('success', 'Permintaan barang berhasil dikirim.');
    }

    public function show(PermintaanBarang $permintaan)
    {
        if ($permintaan->pelanggan_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke permintaan ini.');
        }
        $permintaan->load('detail.barang', 'suratJalan.detail.barang', 'diprosesOleh');
        return view('pelanggan.permintaan.show', compact('permintaan'));
    }
}
