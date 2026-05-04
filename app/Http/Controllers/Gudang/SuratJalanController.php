<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\Stok;
use App\Models\StokMutasi;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
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

        return view('gudang.surat-jalan.index', compact('suratJalan'));
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

        return view('gudang.surat-jalan.create', compact('permintaan', 'selected'));
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
                        'keterangan'   => 'Gudang — distribusi ke ' . ($permintaan->pelanggan->name ?? '-'),
                        'user_id'      => auth()->id(),
                    ]);
                }
            }

            $permintaan->update(['status' => 'siap_kirim']);

            // Notifikasi ke sales (yang proses) dan pelanggan
            $urlSJ = route('gudang.surat-jalan.show', ['suratJalan' => '__ID__']);
            if ($permintaan->diproses_oleh) {
                notif_kirim(
                    $permintaan->diproses_oleh,
                    'Surat Jalan ' . $no_sj . ' dibuat',
                    'Barang untuk ' . $permintaan->no_permintaan . ' sedang disiapkan.',
                    '',
                    'ri:file-text-line',
                    'info'
                );
            }
            notif_kirim(
                $permintaan->pelanggan_id,
                'Pesanan Anda sedang disiapkan',
                'Surat Jalan ' . $no_sj . ' untuk ' . $permintaan->no_permintaan . ' telah dibuat.',
                '',
                'ri:truck-line',
                'success'
            );
        });

        return redirect()->route('gudang.surat-jalan.index')
            ->with('success', 'Surat jalan berhasil dibuat.');
    }

    public function show(SuratJalan $suratJalan)
    {
        $suratJalan->load('permintaan.pelanggan', 'permintaan.detail.barang', 'detail.barang', 'dibuatOleh');

        return view('gudang.surat-jalan.show', compact('suratJalan'));
    }

    public function print(SuratJalan $suratJalan)
    {
        $suratJalan->load('permintaan.pelanggan', 'detail.barang', 'dibuatOleh');

        return view('gudang.surat-jalan.print', compact('suratJalan'));
    }

    public function updateStatus(Request $request, SuratJalan $suratJalan)
    {
        $request->validate([
            'status' => 'required|in:dibuat,dikirim,selesai',
        ]);

        $suratJalan->update(['status' => $request->status]);
        $permintaan = $suratJalan->permintaan;

        if ($request->status === 'dikirim') {
            $permintaan->update(['status' => 'siap_kirim']);

            // Notif sales + pelanggan: barang sudah dikirim
            if ($permintaan->diproses_oleh) {
                notif_kirim(
                    $permintaan->diproses_oleh,
                    'Barang sudah dikirim',
                    $suratJalan->no_sj . ' — ' . ($suratJalan->driver ?? 'driver') . ' sedang mengantar ke pelanggan.',
                    '',
                    'ri:send-plane-line',
                    'info'
                );
            }
            notif_kirim(
                $permintaan->pelanggan_id,
                'Pesanan Anda dalam perjalanan',
                $suratJalan->no_sj . ' sudah dikirim. Driver: ' . ($suratJalan->driver ?? '—') . '.',
                '',
                'ri:truck-line',
                'success'
            );
        } elseif ($request->status === 'selesai') {
            $permintaan->update(['status' => 'selesai']);

            // Notif admin: pengiriman selesai, siap invoice
            notif_kirim_ke_role(
                'admin',
                'Pengiriman selesai — cek invoice',
                $suratJalan->no_sj . ' telah selesai. Periksa invoice untuk ' . ($permintaan->pelanggan->name ?? '—') . '.',
                route('admin.invoice.show', $permintaan),
                'ri:bill-line',
                'success'
            );
            // Notif pelanggan
            notif_kirim(
                $permintaan->pelanggan_id,
                'Pesanan telah tiba',
                'Pengiriman ' . $suratJalan->no_sj . ' sudah selesai. Terima kasih!',
                '',
                'ri:check-double-line',
                'success'
            );
        }

        return back()->with('success', 'Status surat jalan diperbarui.');
    }
}
