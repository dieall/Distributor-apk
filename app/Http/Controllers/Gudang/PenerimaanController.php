<?php

namespace App\Http\Controllers\Gudang;

use App\Http\Controllers\Controller;
use App\Models\PenerimaanBarang;
use App\Models\Pembelian;
use App\Models\Stok;
use App\Models\StokMutasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $query = PenerimaanBarang::with('pembelian.supplier', 'diterima')->latest();

        if ($request->search) {
            $query->where('no_penerimaan', 'like', "%{$request->search}%");
        }

        $penerimaan = $query->paginate(15)->withQueryString();
        return view('gudang.penerimaan.index', compact('penerimaan'));
    }

    public function create()
    {
        $pembelian = Pembelian::with('supplier', 'detail.barang')
            ->whereIn('status', ['dikirim', 'sebagian_diterima'])
            ->latest()
            ->get();
        return view('gudang.penerimaan.create', compact('pembelian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pembelian_id'     => 'required|exists:pembelian,id',
            'tanggal'          => 'required|date',
            'barang_id'        => 'required|array|min:1',
            'jumlah_diterima'  => 'required|array',
            'jumlah_diterima.*'=> 'required|numeric|min:0',
            'harga_satuan'     => 'required|array',
            'harga_satuan.*'   => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $count       = PenerimaanBarang::whereDate('created_at', today())->count() + 1;
            $no_penerimaan = 'TRB-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            $penerimaan = PenerimaanBarang::create([
                'no_penerimaan' => $no_penerimaan,
                'pembelian_id'  => $request->pembelian_id,
                'diterima_oleh' => auth()->id(),
                'tanggal'       => $request->tanggal,
                'status'        => 'selesai',
                'catatan'       => $request->catatan,
            ]);

            foreach ($request->barang_id as $i => $bid) {
                $jumlah = $request->jumlah_diterima[$i];
                if ($jumlah <= 0) continue;

                $harga    = $request->harga_satuan[$i];
                $subtotal = $jumlah * $harga;

                $penerimaan->detail()->create([
                    'barang_id'       => $bid,
                    'jumlah_diterima' => $jumlah,
                    'harga_satuan'    => $harga,
                    'subtotal'        => $subtotal,
                ]);

                // Update stok dengan moving average
                $stok        = Stok::firstOrCreate(['barang_id' => $bid], ['jumlah' => 0, 'harga_rata' => 0]);
                $jml_lama    = (float) $stok->jumlah;
                $harga_lama  = (float) $stok->harga_rata;
                $jml_baru    = $jml_lama + $jumlah;
                $harga_baru  = $jml_baru > 0
                    ? (($jml_lama * $harga_lama) + ($jumlah * $harga)) / $jml_baru
                    : $harga;

                $stok->update(['jumlah' => $jml_baru, 'harga_rata' => $harga_baru]);

                StokMutasi::create([
                    'barang_id'    => $bid,
                    'tipe'         => 'masuk',
                    'jumlah'       => $jumlah,
                    'harga_satuan' => $harga,
                    'referensi'    => $no_penerimaan,
                    'keterangan'   => 'Penerimaan dari ' . $request->pembelian_id,
                    'user_id'      => auth()->id(),
                ]);
            }

            // Update status PO
            Pembelian::find($request->pembelian_id)->update(['status' => 'diterima']);
        });

        // Notif admin: stok bertambah dari penerimaan
        notif_kirim_ke_role(
            'admin',
            'Penerimaan barang dicatat',
            'Gudang mencatat penerimaan dari PO ' . (optional(\App\Models\Pembelian::find($request->pembelian_id))->no_po ?? '-') . '. Stok sudah diperbarui.',
            '',
            'ri:inbox-archive-line',
            'success'
        );

        return redirect()->route('gudang.penerimaan.index')
            ->with('success', 'Penerimaan barang berhasil dicatat.');
    }

    public function show(PenerimaanBarang $penerimaan)
    {
        $penerimaan->load('pembelian.supplier', 'diterima', 'detail.barang');
        return view('gudang.penerimaan.show', compact('penerimaan'));
    }
}
