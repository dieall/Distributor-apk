<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\SuratJalan;
use App\Models\Stok;
use App\Models\StokMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return back()->with('success', 'Form ceklis berhasil disimpan.');
    }

    public function buatSuratJalan(Request $request, PermintaanBarang $permintaan)
    {
        $request->validate([
            'tanggal'       => 'required|date',
            'driver'        => 'nullable|string|max:100',
            'no_kendaraan'  => 'nullable|string|max:20',
            'alamat_tujuan' => 'nullable|string|max:500',
            'catatan'       => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $permintaan) {
            $count = SuratJalan::whereDate('created_at', today())->count() + 1;
            $no_sj = 'SJ-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $sj = SuratJalan::create([
                'no_sj'          => $no_sj,
                'permintaan_id'  => $permintaan->id,
                'dibuat_oleh'    => auth()->id(),
                'tanggal'        => $request->tanggal,
                'driver'         => $request->driver,
                'no_kendaraan'   => $request->no_kendaraan,
                'alamat_tujuan'  => $request->alamat_tujuan,
                'status'         => 'dibuat',
                'catatan'        => $request->catatan,
            ]);

            foreach ($permintaan->detail()->where('is_checked', true)->get() as $d) {
                $sj->detail()->create([
                    'barang_id' => $d->barang_id,
                    'jumlah'    => $d->jumlah_disetujui,
                ]);

                $stok = Stok::where('barang_id', $d->barang_id)->first();
                if ($stok && $stok->jumlah >= $d->jumlah_disetujui) {
                    $stok->decrement('jumlah', $d->jumlah_disetujui);
                    StokMutasi::create([
                        'barang_id'    => $d->barang_id,
                        'tipe'         => 'keluar',
                        'jumlah'       => $d->jumlah_disetujui,
                        'harga_satuan' => $stok->harga_rata,
                        'referensi'    => $no_sj,
                        'keterangan'   => 'Distribusi ke ' . ($permintaan->pelanggan->name ?? '-'),
                        'user_id'      => auth()->id(),
                    ]);
                }
            }

            $permintaan->update(['status' => 'siap_kirim']);
        });

        return redirect()->route('sales.permintaan.show', $permintaan)
            ->with('success', 'Surat jalan berhasil dibuat.');
    }

    public function kirimSuratJalan(SuratJalan $suratJalan)
    {
        $suratJalan->update(['status' => 'dikirim']);
        $suratJalan->permintaan->update(['status' => 'selesai']);
        return back()->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}
