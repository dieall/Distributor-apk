<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use Illuminate\Http\Request;

class PermintaanController extends Controller
{
    public function index(Request $request)
    {
        $query = PermintaanBarang::with('pelanggan', 'diprosesOleh')->latest();

        if ($request->search) {
            $query->where('no_permintaan', 'like', "%{$request->search}%")
                  ->orWhereHas('pelanggan', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $permintaan = $query->paginate(15)->withQueryString();

        $counts = [
            'pending'    => PermintaanBarang::where('status', 'pending')->count(),
            'diproses'   => PermintaanBarang::where('status', 'diproses')->count(),
            'siap_kirim' => PermintaanBarang::where('status', 'siap_kirim')->count(),
        ];

        return view('sales.permintaan.index', compact('permintaan', 'counts'));
    }

    public function show(PermintaanBarang $permintaan)
    {
        $permintaan->load('pelanggan', 'detail.barang.stok', 'suratJalan.detail.barang', 'diprosesOleh');
        return view('sales.permintaan.show', compact('permintaan'));
    }

    public function ceklis(Request $request, PermintaanBarang $permintaan)
    {
        $request->validate([
            'items'                    => 'required|array',
            'items.*.id'               => 'required|exists:permintaan_detail,id',
            'items.*.jumlah_disetujui' => 'required|numeric|min:0',
            'items.*.harga_jual'       => 'required|numeric|min:0',
            'items.*.is_checked'       => 'sometimes|boolean',
        ]);

        foreach ($request->items as $item) {
            $jumlah = (float) $item['jumlah_disetujui'];
            $hargaJual = (float) $item['harga_jual'];
            $permintaan->detail()->where('id', $item['id'])->update([
                'jumlah_disetujui' => $jumlah,
                'harga_jual'       => $hargaJual,
                'subtotal_jual'    => $jumlah * $hargaJual,
                'is_checked'       => $item['is_checked'] ?? false,
            ]);
        }

        $permintaan->update(['status' => 'diproses', 'diproses_oleh' => auth()->id()]);

        // Notifikasi ke semua gudang: permintaan siap dibuatkan surat jalan
        notif_kirim_ke_role(
            'gudang',
            'Permintaan siap dikirim',
            'Ceklis ' . $permintaan->no_permintaan . ' sudah disetujui oleh sales. Silakan buat Surat Jalan.',
            '',
            'ri:truck-line',
            'warning'
        );

        return back()->with('success', 'Form ceklis berhasil disimpan.');
    }

    public function create()
    {
        $barang = \App\Models\Barang::with('stok')->where('is_active', true)->get();
        $pelanggan = \App\Models\User::where('role', 'pelanggan')->where('is_active', true)->get();
        return view('sales.permintaan.create', compact('barang', 'pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'       => 'required|exists:users,id',
            'no_po_customer'     => 'nullable|string|max:50',
            'alamat'             => 'required|string|max:255',
            'no_hp'              => 'required|string|max:20',
            'tanggal_dibutuhkan' => 'nullable|date',
            'catatan'            => 'nullable|string',
            'barang_id'          => 'required|array|min:1',
            'barang_id.*'        => 'required|exists:barang,id',
            'jumlah_diminta'     => 'required|array',
            'jumlah_diminta.*'   => 'required|numeric|min:0.01',
        ]);

        foreach ($request->barang_id as $bid) {
            if (! isset($request->jumlah_diminta[$bid])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jumlah_diminta' => 'Jumlah untuk setiap barang yang dipilih wajib diisi.',
                ]);
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $count = PermintaanBarang::whereDate('created_at', today())->count() + 1;
            $no    = 'REQ-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $permintaan = PermintaanBarang::create([
                'no_permintaan'      => $no,
                'no_po_customer'     => $request->no_po_customer,
                'pelanggan_id'       => $request->pelanggan_id,
                'alamat'             => $request->alamat,
                'no_hp'              => $request->no_hp,
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

        return redirect()->route('sales.permintaan.index')
            ->with('success', 'Permintaan barang berhasil dibuat.');
    }

    public function edit(PermintaanBarang $permintaan)
    {
        if ($permintaan->status !== 'pending') {
            return redirect()->route('sales.permintaan.index')
                ->with('error', 'Permintaan yang sudah diproses tidak dapat diedit.');
        }

        $barang = \App\Models\Barang::with('stok')->where('is_active', true)->get();
        $pelanggan = \App\Models\User::where('role', 'pelanggan')->where('is_active', true)->get();
        $permintaan->load('detail');

        return view('sales.permintaan.edit', compact('permintaan', 'barang', 'pelanggan'));
    }

    public function update(Request $request, PermintaanBarang $permintaan)
    {
        if ($permintaan->status !== 'pending') {
            return redirect()->route('sales.permintaan.index')
                ->with('error', 'Permintaan yang sudah diproses tidak dapat diedit.');
        }

        $request->validate([
            'pelanggan_id'       => 'required|exists:users,id',
            'no_po_customer'     => 'nullable|string|max:50',
            'alamat'             => 'required|string|max:255',
            'no_hp'              => 'required|string|max:20',
            'tanggal_dibutuhkan' => 'nullable|date',
            'catatan'            => 'nullable|string',
            'barang_id'          => 'required|array|min:1',
            'barang_id.*'        => 'required|exists:barang,id',
            'jumlah_diminta'     => 'required|array',
            'jumlah_diminta.*'   => 'required|numeric|min:0.01',
        ]);

        foreach ($request->barang_id as $bid) {
            if (! isset($request->jumlah_diminta[$bid])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'jumlah_diminta' => 'Jumlah untuk setiap barang yang dipilih wajib diisi.',
                ]);
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $permintaan) {
            $permintaan->update([
                'no_po_customer'     => $request->no_po_customer,
                'pelanggan_id'       => $request->pelanggan_id,
                'alamat'             => $request->alamat,
                'no_hp'              => $request->no_hp,
                'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
                'catatan'            => $request->catatan,
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

        return redirect()->route('sales.permintaan.index')
            ->with('success', 'Permintaan barang berhasil diperbarui.');
    }

    public function destroy(PermintaanBarang $permintaan)
    {
        if ($permintaan->status !== 'pending') {
            return redirect()->route('sales.permintaan.index')
                ->with('error', 'Permintaan yang sudah diproses tidak dapat dihapus.');
        }

        $permintaan->delete();

        return redirect()->route('sales.permintaan.index')
            ->with('success', 'Permintaan barang berhasil dihapus.');
    }
}
