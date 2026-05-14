<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\PermintaanDetail;
use App\Models\Stok;
use App\Models\StokMutasi;
use App\Models\SuratJalanDetail;
use App\Models\User;
use App\Services\HtmlExcelExport;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = PermintaanBarang::with('pelanggan', 'suratJalan')
            ->whereIn('status', ['siap_kirim', 'selesai'])
            ->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('no_permintaan', 'like', "%{$s}%")
                ->orWhereHas('pelanggan', fn ($q) => $q->where('name', 'like', "%{$s}%"));
        }

        if ($request->filled('pelanggan_id')) {
            $query->where('pelanggan_id', $request->pelanggan_id);
        }

        $permintaan = $query->paginate(15)->withQueryString();
        $pelanggans = User::where('role', 'pelanggan')->orderBy('name')->get();

        return view('admin.invoice.index', compact('permintaan', 'pelanggans'));
    }

    public function show(PermintaanBarang $permintaan)
    {
        $permintaan->load('pelanggan', 'detail.barang', 'suratJalan', 'diprosesOleh');

        return view('admin.invoice.show', compact('permintaan'));
    }

    public function export(Request $request)
    {
        $query = PermintaanBarang::with('pelanggan', 'suratJalan', 'detail.barang')
            ->whereIn('status', ['siap_kirim', 'selesai'])
            ->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('no_permintaan', 'like', "%{$s}%")
                    ->orWhereHas('pelanggan', fn ($subQ) => $subQ->where('name', 'like', "%{$s}%"));
            });
        }

        if ($request->filled('pelanggan_id')) {
            $query->where('pelanggan_id', $request->pelanggan_id);
        }

        $data = $query->get();

        $stem = 'invoice_pelanggan';
        if ($request->filled('pelanggan_id')) {
            $stem .= '_pelanggan_'.$request->pelanggan_id;
        }
        $stem .= '_'.now()->format('Ymd_His');

        return HtmlExcelExport::invoicePelanggan($data, $stem);
    }

    public function print(Request $request, PermintaanBarang $permintaan)
    {
        $permintaan->load('pelanggan', 'detail.barang', 'suratJalan', 'diprosesOleh');

        $c = config('company');
        $invoiceDate = $request->query('invoice_date', optional($permintaan->tanggal_request)->format('Y-m-d'));
        try {
            $invoiceDate = Carbon::parse((string) $invoiceDate)->format('Y-m-d');
        } catch (\Throwable $th) {
            $invoiceDate = optional($permintaan->tanggal_request)->format('Y-m-d');
        }

        $printData = [
            'company_name' => trim((string) $request->query('company_name', $c['legal_name'] ?? '')),
            'company_address' => trim((string) $request->query('company_address', ($c['address'] ?? '') . (!empty($c['phone']) ? ' | Telp: ' . $c['phone'] : ''))),
            'invoice_date' => $invoiceDate,
            'bank_name' => trim((string) $request->query('bank_name', 'BCA / Mandiri')),
            'bank_account_number' => trim((string) $request->query('bank_account_number', '123-456-789')),
            'bank_account_holder' => trim((string) $request->query('bank_account_holder', $c['legal_name'] ?? '')),
            'receiver_name' => trim((string) $request->query('receiver_name', $permintaan->pelanggan->name ?? '')),
            'bill_to_name' => trim((string) $request->query('bill_to_name', $permintaan->pelanggan->name ?? '')),
            'bill_to_phone' => trim((string) $request->query('bill_to_phone', $permintaan->pelanggan->phone ?? '')),
            'bill_to_address' => trim((string) $request->query('bill_to_address', $permintaan->pelanggan->address ?? '')),
            'no_po_customer' => trim((string) $request->query('no_po_customer', $permintaan->no_po_customer ?? '')),
        ];

        return view('admin.invoice.print', compact('permintaan', 'printData'));
    }

    public function updateItem(Request $request, PermintaanBarang $permintaan, PermintaanDetail $detail)
    {
        abort_unless($detail->permintaan_id === $permintaan->id, 404);

        $validated = $request->validate([
            'jumlah_disetujui' => 'required|numeric|min:0.01',
            'harga_jual'       => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $permintaan, $detail) {
            $detail = PermintaanDetail::whereKey($detail->id)->lockForUpdate()->firstOrFail();
            $oldQty = (float) $detail->jumlah_disetujui;
            $newQty = (float) $validated['jumlah_disetujui'];
            $newPrice = (float) $validated['harga_jual'];
            $qtyDiff = $newQty - $oldQty;

            $suratJalan = $permintaan->suratJalan;

            if ($suratJalan && abs($qtyDiff) > 0.00001) {
                $suratJalanDetail = SuratJalanDetail::where('surat_jalan_id', $suratJalan->id)
                    ->where('barang_id', $detail->barang_id)
                    ->lockForUpdate()
                    ->first();

                if (! $suratJalanDetail) {
                    throw ValidationException::withMessages([
                        'jumlah_disetujui' => 'Detail surat jalan untuk barang ini tidak ditemukan.',
                    ]);
                }

                $stok = Stok::where('barang_id', $detail->barang_id)->lockForUpdate()->first();

                if (! $stok) {
                    throw ValidationException::withMessages([
                        'jumlah_disetujui' => 'Stok barang tidak ditemukan.',
                    ]);
                }

                if ($qtyDiff > 0 && (float) $stok->jumlah < $qtyDiff) {
                    throw ValidationException::withMessages([
                        'jumlah_disetujui' => 'Stok tidak mencukupi untuk tambahan koreksi ' . format_qty_id($qtyDiff) . '.',
                    ]);
                }

                if ($qtyDiff > 0) {
                    $stok->decrement('jumlah', $qtyDiff);
                    $mutationType = 'keluar';
                    $mutationNote = 'Koreksi invoice - tambahan distribusi';
                } else {
                    $stok->increment('jumlah', abs($qtyDiff));
                    $mutationType = 'masuk';
                    $mutationNote = 'Koreksi invoice - pengembalian stok';
                }

                StokMutasi::create([
                    'barang_id'    => $detail->barang_id,
                    'tipe'         => $mutationType,
                    'jumlah'       => abs($qtyDiff),
                    'harga_satuan' => $stok->harga_rata,
                    'referensi'    => $suratJalan->no_sj,
                    'keterangan'   => $mutationNote . ' ' . $permintaan->no_permintaan,
                    'user_id'      => Auth::id(),
                ]);

                $suratJalanDetail->update(['jumlah' => $newQty]);
            }

            $detail->update([
                'jumlah_disetujui' => $newQty,
                'harga_jual'       => $newPrice,
                'subtotal_jual'    => $newQty * $newPrice,
            ]);
        });

        return back()->with('success', 'Item invoice berhasil dikoreksi. Total, subtotal, surat jalan, dan stok sudah disesuaikan.');
    }
}
