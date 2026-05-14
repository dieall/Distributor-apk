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
            'tanggal_dibutuhkan' => 'nullable|date',
            'catatan'            => 'nullable|string',
            'no_po_customer'     => 'nullable|string|max:50',
            'barang_id'          => 'required|array|min:1',
            'barang_id.*'        => 'required|exists:barang,id',
            'jumlah_diminta'     => 'required|array',
            'jumlah_diminta.*'   => 'required|numeric|min:0.01',
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
                'no_po_customer'     => $request->no_po_customer,
            ]);

            foreach ($request->barang_id as $bid) {
                $permintaan->detail()->create([
                    'barang_id'        => $bid,
                    'jumlah_diminta'   => $request->jumlah_diminta[$bid],
                    'jumlah_disetujui' => 0,
                ]);
            }
        });

        // Notifikasi ke semua sales: ada permintaan baru
        notif_kirim_ke_role(
            'sales',
            'Permintaan baru masuk',
            'Pelanggan ' . auth()->user()->name . ' mengirim permintaan barang.',
            '',
            'ri:file-list-3-line',
            'primary'
        );

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

    public function edit(PermintaanBarang $permintaan)
    {
        if ($permintaan->pelanggan_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke permintaan ini.');
        }

        if ($permintaan->status !== 'pending') {
            return redirect()->route('pelanggan.permintaan.index')
                ->with('error', 'Permintaan yang sudah diproses tidak dapat diedit.');
        }

        $barang = Barang::with('stok')->where('is_active', true)->get();
        $permintaan->load('detail');

        return view('pelanggan.permintaan.edit', compact('permintaan', 'barang'));
    }

    public function update(Request $request, PermintaanBarang $permintaan)
    {
        if ($permintaan->pelanggan_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke permintaan ini.');
        }

        if ($permintaan->status !== 'pending') {
            return redirect()->route('pelanggan.permintaan.index')
                ->with('error', 'Permintaan yang sudah diproses tidak dapat diedit.');
        }

        $request->validate([
            'tanggal_dibutuhkan' => 'nullable|date',
            'catatan'            => 'nullable|string',
            'no_po_customer'     => 'nullable|string|max:50',
            'barang_id'          => 'required|array|min:1',
            'barang_id.*'        => 'required|exists:barang,id',
            'jumlah_diminta'     => 'required|array',
            'jumlah_diminta.*'   => 'required|numeric|min:0.01',
        ]);

        foreach ($request->barang_id as $bid) {
            if (! isset($request->jumlah_diminta[$bid])) {
                throw ValidationException::withMessages([
                    'jumlah_diminta' => 'Jumlah untuk setiap barang yang dipilih wajib diisi.',
                ]);
            }
        }

        DB::transaction(function () use ($request, $permintaan) {
            $permintaan->update([
                'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
                'catatan'            => $request->catatan,
                'no_po_customer'     => $request->no_po_customer,
            ]);

            $permintaan->detail()->delete();

            foreach ($request->barang_id as $bid) {
                $permintaan->detail()->create([
                    'barang_id'        => $bid,
                    'jumlah_diminta'   => $request->jumlah_diminta[$bid],
                    'jumlah_disetujui' => 0,
                ]);
            }
        });

        return redirect()->route('pelanggan.permintaan.index')
            ->with('success', 'Permintaan barang berhasil diperbarui.');
    }

    public function destroy(PermintaanBarang $permintaan)
    {
        if ($permintaan->pelanggan_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke permintaan ini.');
        }

        if ($permintaan->status !== 'pending') {
            return redirect()->route('pelanggan.permintaan.index')
                ->with('error', 'Permintaan yang sudah diproses tidak dapat dihapus.');
        }

        $permintaan->delete();

        return redirect()->route('pelanggan.permintaan.index')
            ->with('success', 'Permintaan barang berhasil dihapus.');
    }
}
