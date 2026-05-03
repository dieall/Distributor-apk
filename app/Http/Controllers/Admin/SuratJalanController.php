<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use App\Models\Stok;
use App\Models\StokMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratJalanController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratJalan::with('permintaan.pelanggan', 'dibuatOleh')
            ->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('no_sj', 'like', "%{$s}%")
                ->orWhereHas('permintaan.pelanggan', fn ($q) => $q->where('name', 'like', "%{$s}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $suratJalan = $query->paginate(15)->withQueryString();

        return view('admin.surat-jalan.index', compact('suratJalan'));
    }

    public function create(Request $request)
    {
        $permintaan = PermintaanBarang::with('pelanggan', 'detail.barang')
            ->whereIn('status', ['diproses'])
            ->whereDoesntHave('suratJalan')
            ->orderBy('tanggal_request', 'asc')
            ->get();

        $selected = null;
        if ($request->filled('permintaan_id')) {
            $selected = PermintaanBarang::with('pelanggan', 'detail.barang')
                ->findOrFail($request->permintaan_id);
        }

        return view('admin.surat-jalan.create', compact('permintaan', 'selected'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'permintaan_id' => 'required|exists:permintaan_barang,id',
            'tanggal'       => 'required|date',
            'driver'        => 'nullable|string|max:100',
            'no_kendaraan'  => 'nullable|string|max:20',
            'alamat_tujuan' => 'nullable|string|max:500',
            'catatan'       => 'nullable|string',
        ]);

        $permintaan = PermintaanBarang::with('detail.barang', 'pelanggan')
            ->findOrFail($request->permintaan_id);

        if ($permintaan->suratJalan) {
            return back()->with('error', 'Permintaan ini sudah memiliki surat jalan.');
        }

        DB::transaction(function () use ($request, $permintaan) {
            $count = SuratJalan::whereDate('created_at', today())->count() + 1;
            $no_sj = 'SJ-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $sj = SuratJalan::create([
                'no_sj'         => $no_sj,
                'permintaan_id' => $permintaan->id,
                'dibuat_oleh'   => auth()->id(),
                'tanggal'       => $request->tanggal,
                'driver'        => $request->driver,
                'no_kendaraan'  => $request->no_kendaraan,
                'alamat_tujuan' => $request->alamat_tujuan,
                'status'        => 'dibuat',
                'catatan'       => $request->catatan,
            ]);

            foreach ($permintaan->detail()->where('is_checked', true)->get() as $d) {
                SuratJalanDetail::create([
                    'surat_jalan_id' => $sj->id,
                    'barang_id'      => $d->barang_id,
                    'jumlah'         => $d->jumlah_disetujui,
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
                        'keterangan'   => 'Admin — distribusi ke ' . ($permintaan->pelanggan->name ?? '-'),
                        'user_id'      => auth()->id(),
                    ]);
                }
            }

            $permintaan->update(['status' => 'siap_kirim']);
        });

        return redirect()->route('admin.surat-jalan.index')
            ->with('success', 'Surat jalan berhasil dibuat.');
    }

    public function show(SuratJalan $suratJalan)
    {
        $suratJalan->load('permintaan.pelanggan', 'permintaan.detail.barang', 'detail.barang', 'dibuatOleh');

        return view('admin.surat-jalan.show', compact('suratJalan'));
    }

    public function print(SuratJalan $suratJalan)
    {
        $suratJalan->load('permintaan.pelanggan', 'detail.barang', 'dibuatOleh');

        return view('admin.surat-jalan.print', compact('suratJalan'));
    }

    public function updateStatus(Request $request, SuratJalan $suratJalan)
    {
        $request->validate([
            'status' => 'required|in:dibuat,dikirim,selesai',
        ]);

        $suratJalan->update(['status' => $request->status]);

        if ($request->status === 'selesai') {
            $suratJalan->permintaan->update(['status' => 'selesai']);
        } elseif ($request->status === 'dikirim') {
            $suratJalan->permintaan->update(['status' => 'siap_kirim']);
        }

        return back()->with('success', 'Status surat jalan diperbarui.');
    }
}
