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
}
