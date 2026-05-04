<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermintaanBarang;
use App\Models\User;
use App\Services\HtmlExcelExport;
use Illuminate\Http\Request;

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
        $query = PermintaanBarang::with('pelanggan', 'suratJalan', 'detail')
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

    public function print(PermintaanBarang $permintaan)
    {
        $permintaan->load('pelanggan', 'detail.barang', 'suratJalan', 'diprosesOleh');

        return view('admin.invoice.print', compact('permintaan'));
    }
}
