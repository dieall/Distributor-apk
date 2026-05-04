<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PengeluaranBarang;
use App\Models\Stok;
use App\Models\StokMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PengeluaranBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = PengeluaranBarang::with('dibuatOleh')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('no_pengeluaran', 'like', "%{$s}%");
        }

        $items = $query->paginate(15)->withQueryString();

        return view('gudang.pengeluaran-barang.index', compact('items'));
    }

    public function create()
    {
        $barang = Barang::with('stok')->where('is_active', true)->orderBy('nama')->get();
        $alasanOpsi = PengeluaranBarang::ALASAN_OPSI;

        return view('gudang.pengeluaran-barang.create', compact('barang', 'alasanOpsi'));
    }

    public function store(Request $request)
    {
        $alasanKeys = implode(',', array_keys(PengeluaranBarang::ALASAN_OPSI));

        $request->validate([
            'tanggal'              => 'required|date',
            'alasan'               => 'required|in:' . $alasanKeys,
            'catatan'              => 'nullable|string|max:2000',
            'barang_id'            => 'required|array|min:1',
            'barang_id.*'          => 'nullable|exists:barang,id',
            'jumlah_keluar'        => 'required|array',
            'jumlah_keluar.*'      => 'nullable|numeric|min:0',
            'keterangan_detail'    => 'nullable|array',
            'keterangan_detail.*'  => 'nullable|string|max:255',
        ]);

        $lines = [];
        foreach ($request->barang_id as $i => $bid) {
            if (empty($bid)) {
                continue;
            }
            $qty = (float) ($request->jumlah_keluar[$i] ?? 0);
            if ($qty <= 0) {
                continue;
            }
            $lines[] = [
                'barang_id'   => (int) $bid,
                'jumlah'      => $qty,
                'keterangan'  => $request->keterangan_detail[$i] ?? null,
            ];
        }

        if ($lines === []) {
            throw ValidationException::withMessages([
                'barang_id' => 'Isi minimal satu barang dengan jumlah keluar lebih dari 0.',
            ]);
        }

        DB::transaction(function () use ($request, $lines) {
            $count = PengeluaranBarang::whereDate('created_at', today())->count() + 1;
            $no = 'PB-' . date('Ymd') . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);

            $header = PengeluaranBarang::create([
                'no_pengeluaran' => $no,
                'alasan'         => $request->alasan,
                'tanggal'        => $request->tanggal,
                'catatan'        => $request->catatan,
                'dibuat_oleh'    => auth()->id(),
            ]);

            foreach ($lines as $line) {
                $stok = Stok::where('barang_id', $line['barang_id'])->lockForUpdate()->first();
                if (! $stok || (float) $stok->jumlah < $line['jumlah']) {
                    $b = Barang::find($line['barang_id']);
                    $nama = $b?->nama ?? 'Barang';
                    throw ValidationException::withMessages([
                        'barang_id' => "Stok tidak cukup untuk: {$nama}.",
                    ]);
                }

                $hargaRata = (float) $stok->harga_rata;

                $header->detail()->create([
                    'barang_id'      => $line['barang_id'],
                    'jumlah_keluar'  => $line['jumlah'],
                    'keterangan'     => $line['keterangan'],
                ]);

                $stok->decrement('jumlah', $line['jumlah']);

                StokMutasi::create([
                    'barang_id'    => $line['barang_id'],
                    'tipe'         => 'keluar',
                    'jumlah'       => $line['jumlah'],
                    'harga_satuan' => $hargaRata,
                    'referensi'    => $no,
                    'keterangan'   => 'Pengeluaran barang — ' . (PengeluaranBarang::ALASAN_OPSI[$request->alasan] ?? $request->alasan),
                    'user_id'      => auth()->id(),
                ]);
            }
        });

        // Notif admin: ada pengeluaran stok dari gudang
        notif_kirim_ke_role(
            'admin',
            'Pengeluaran barang dicatat',
            'Gudang mencatat pengeluaran barang dengan alasan: ' . (\App\Models\PengeluaranBarang::ALASAN_OPSI[$request->alasan] ?? $request->alasan) . '. Stok sudah dikurangi.',
            '',
            'ri:logout-box-r-line',
            'danger'
        );

        return redirect()->route('gudang.pengeluaran-barang.index')
            ->with('success', 'Pengeluaran barang berhasil dicatat dan stok telah berkurang.');
    }

    public function show(PengeluaranBarang $pengeluaran_barang)
    {
        $pengeluaran_barang->load('dibuatOleh', 'detail.barang');

        return view('gudang.pengeluaran-barang.show', ['pb' => $pengeluaran_barang]);
    }
}
