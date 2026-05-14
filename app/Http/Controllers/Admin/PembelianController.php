<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\Barang;
use App\Models\Supplier;
use App\Models\User;
use App\Services\HtmlExcelExport;
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

    /**
     * Export PO ke Excel (.xls HTML): satu baris per barang, blok PO digabung (rowspan).
     */
    public function export(Request $request)
    {
        $query = Pembelian::with('supplier', 'dibuatOleh', 'detail.barang')->latest();

        if ($request->filled('search')) {
            $query->where('no_po', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->get();

        $stem = 'purchase_order_detail';
        if ($request->filled('search')) {
            $stem .= '_'.$this->safeExportStem($request->search);
        }
        if ($request->filled('status')) {
            $stem .= '_'.$request->status;
        }
        $stem .= '_'.now()->format('Ymd_His');

        return HtmlExcelExport::pembelianPo($data, $stem);
    }

    private function safeExportStem(string $s): string
    {
        return preg_replace('/[^a-zA-Z0-9_-]+/', '_', substr($s, 0, 40)) ?: 'filter';
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
            'bukti_pembayaran'         => 'nullable|array|max:5',
            'bukti_pembayaran.*'       => 'file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ]);

        // Handle file upload
        $buktiFoto = [];
        if ($request->hasFile('bukti_pembayaran')) {
            foreach ($request->file('bukti_pembayaran') as $file) {
                $buktiFoto[] = $file->store('bukti-pembayaran', 'public');
            }
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

    public function buktiPembayaran(string $path)
    {
        $normalizedPath = str_replace('\\', '/', ltrim($path, '/'));

        if (str_starts_with($normalizedPath, 'storage/')) {
            $normalizedPath = substr($normalizedPath, 8);
        }

        if (str_starts_with($normalizedPath, 'public/')) {
            $normalizedPath = substr($normalizedPath, 7);
        }

        abort_unless(str_starts_with($normalizedPath, 'bukti-pembayaran/'), 404);
        abort_unless(Storage::disk('public')->exists($normalizedPath), 404);

        return response()->file(storage_path('app/public/' . $normalizedPath));
    }

    public function edit(Pembelian $pembelian)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        $pembelian->load('detail');
        $suppliers = Supplier::orderBy('name')->get();
        $barang    = Barang::where('is_active', true)->get();
        return view('admin.pembelian.edit', compact('pembelian', 'suppliers', 'barang'));
    }

    public function update(Request $request, Pembelian $pembelian)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        $rules = [
            'supplier_id'              => 'required|exists:suppliers,id',
            'tanggal'                  => 'required|date',
            'tanggal_kirim_estimasi'   => 'nullable|date|after_or_equal:tanggal',
            'barang_id'                => 'required|array|min:1',
            'barang_id.*'              => 'required|exists:barang,id',
            'jumlah.*'                 => 'required|numeric|min:1',
            'harga_satuan.*'           => 'required|numeric|min:0',
            'bukti_pembayaran'         => 'nullable|array|max:5',
            'bukti_pembayaran.*'       => 'file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ];

        if (auth()->user()->isAdmin()) {
            $rules['status'] = 'nullable|in:draft,dikirim,sebagian_diterima,diterima,dibatalkan';
        }

        $request->validate($rules);

        $buktiFoto = $pembelian->bukti_pembayaran ?? [];
        if ($request->hasFile('bukti_pembayaran')) {
            foreach ($buktiFoto as $oldFile) {
                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $buktiFoto = [];
            foreach ($request->file('bukti_pembayaran') as $file) {
                $buktiFoto[] = $file->store('bukti-pembayaran', 'public');
            }
        }

        DB::transaction(function () use ($request, $pembelian, $buktiFoto) {
            $total = 0;
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

            $updateData = [
                'supplier_id'            => $request->supplier_id,
                'tanggal'                => $request->tanggal,
                'tanggal_kirim_estimasi' => $request->tanggal_kirim_estimasi,
                'total'                  => $total,
                'catatan'                => $request->catatan,
                'bukti_pembayaran'       => $buktiFoto,
            ];

            if (auth()->user()->isAdmin() && $request->filled('status')) {
                $updateData['status'] = $request->status;
            }

            $pembelian->update($updateData);

            $pembelian->detail()->delete();
            foreach ($details as $d) {
                $pembelian->detail()->create($d);
            }
        });

        return redirect()->route(fin_route_n('pembelian.index'))
            ->with('success', 'Purchase Order berhasil diperbarui.');
    }

    public function destroy(Pembelian $pembelian)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        
        $buktiFoto = $pembelian->bukti_pembayaran ?? [];
        foreach ($buktiFoto as $oldFile) {
            if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                Storage::disk('public')->delete($oldFile);
            }
        }
        
        $pembelian->detail()->delete();
        $pembelian->delete();

        return redirect()->route(fin_route_n('pembelian.index'))
            ->with('success', 'Purchase Order berhasil dihapus.');
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
