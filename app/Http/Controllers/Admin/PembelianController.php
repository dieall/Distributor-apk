<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembelian::with('supplier', 'dibuatOleh')->latest();

        if ($request->search) {
            $query->where('no_po', 'like', "%{$request->search}%");
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $pembelian = $query->paginate(15)->withQueryString();
        return view('admin.pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        $suppliers = Supplier::orderBy('name')->get();
        $barang    = Barang::where('is_active', true)->get();
        return view('admin.pembelian.create', compact('suppliers', 'barang'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        $request->validate([
            'supplier_id'              => 'required|exists:suppliers,id',
            'tanggal'                  => 'required|date',
            'tanggal_kirim_estimasi'   => 'nullable|date|after_or_equal:tanggal',
            'barang_id'                => 'required|array|min:1',
            'barang_id.*'              => 'required|exists:barang,id',
            'jumlah.*'                 => 'required|numeric|min:1',
            'harga_satuan.*'           => 'required|numeric|min:0',
            'bukti_pembayaran'         => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        // Handle file upload
        $buktiFoto = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiFoto = $request->file('bukti_pembayaran')
                ->store('bukti-pembayaran', 'public');
        }

        DB::transaction(function () use ($request, $buktiFoto) {
            $count  = Pembelian::whereDate('created_at', today())->count() + 1;
            $no_po  = 'PO-' . date('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            $total  = 0;
            $details = [];

            foreach ($request->barang_id as $i => $bid) {
                $jumlah   = $request->jumlah[$i];
                $harga    = $request->harga_satuan[$i];
                $subtotal = $jumlah * $harga;
                $total   += $subtotal;
                $details[] = [
                    'barang_id'    => $bid,
                    'jumlah'       => $jumlah,
                    'harga_satuan' => $harga,
                    'subtotal'     => $subtotal,
                ];
            }

            $pembelian = Pembelian::create([
                'no_po'                  => $no_po,
                'supplier_id'            => $request->supplier_id,
                'dibuat_oleh'            => auth()->id(),
                'tanggal'                => $request->tanggal,
                'tanggal_kirim_estimasi' => $request->tanggal_kirim_estimasi,
                'status'                 => 'draft',
                'total'                  => $total,
                'catatan'                => $request->catatan,
                'bukti_pembayaran'       => $buktiFoto,
            ]);

            foreach ($details as $d) {
                $pembelian->detail()->create($d);
            }
        });

        return redirect()->route(fin_route_n('pembelian.index'))
            ->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function show(Pembelian $pembelian)
    {
        $pembelian->load('supplier', 'dibuatOleh', 'detail.barang', 'penerimaan.detail.barang');
        return view('admin.pembelian.show', compact('pembelian'));
    }

    public function updateStatus(Request $request, Pembelian $pembelian)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        $request->validate([
            'status' => 'required|in:draft,dikirim,sebagian_diterima,diterima,dibatalkan',
        ]);
        $pembelian->update(['status' => $request->status]);

        // Notif gudang jika PO sudah dikirim (siap diterima)
        if ($request->status === 'dikirim') {
            notif_kirim_ke_role(
                'gudang',
                'PO ' . $pembelian->no_po . ' sudah dikirim supplier',
                'Barang dari ' . ($pembelian->supplier->name ?? '—') . ' sedang dalam perjalanan. Siapkan penerimaan.',
                '',
                'ri:inbox-archive-line',
                'warning'
            );
        }

        return back()->with('success', 'Status PO berhasil diperbarui.');
    }
}
