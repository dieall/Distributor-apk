@extends('layouts.app')
@section('title', 'Invoice ' . $permintaan->no_permintaan)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Invoice</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">{{ $permintaan->no_permintaan }}</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('admin.invoice.print', $permintaan) }}" target="_blank"
            class="px-4 py-2 rounded-lg bg-neutral-800 hover:bg-neutral-900 text-white text-sm font-medium flex items-center gap-2">
            <iconify-icon icon="ri:printer-line"></iconify-icon> Cetak Invoice
        </a>
        @if($permintaan->suratJalan)
        <a href="{{ route('gudang.surat-jalan.show', $permintaan->suratJalan) }}"
            class="px-4 py-2 rounded-lg bg-info-100 text-info-700 hover:bg-info-200 text-sm font-medium flex items-center gap-2">
            <iconify-icon icon="ri:truck-line"></iconify-icon> Lihat Surat Jalan
        </a>
        @endif
        <a href="{{ route('admin.invoice.index') }}"
            class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 flex items-center gap-2">
            <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali
        </a>
    </div>
</div>

@include('partials.alert')

@php
    $subtotal = $permintaan->detail->where('is_checked', true)->sum('subtotal_jual');
    $c = config('company');
    $defaultCompanyAddress = ($c['address'] ?? '') . (!empty($c['phone']) ? ' | Telp: ' . $c['phone'] : '');

    $invoicePrintDefaults = [
        'company_name' => request('company_name', $c['legal_name'] ?? ''),
        'company_address' => request('company_address', $defaultCompanyAddress),
        'invoice_date' => request('invoice_date', optional($permintaan->tanggal_request)->format('Y-m-d')),
        'bank_name' => request('bank_name', 'BCA / Mandiri'),
        'bank_account_number' => request('bank_account_number', '123-456-789'),
        'bank_account_holder' => request('bank_account_holder', $c['legal_name'] ?? ''),
        'receiver_name' => request('receiver_name', $permintaan->pelanggan->name ?? ''),
        'bill_to_name' => request('bill_to_name', $permintaan->pelanggan->name ?? ''),
        'bill_to_phone' => request('bill_to_phone', $permintaan->pelanggan->phone ?? ''),
        'bill_to_address' => request('bill_to_address', $permintaan->pelanggan->address ?? ''),
        'no_po_customer' => request('no_po_customer', $permintaan->no_po_customer ?? ''),
    ];
@endphp

<div class="w-full space-y-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 w-full">
        <div class="flex items-center justify-between gap-2 mb-4">
            <p class="text-xs font-bold uppercase text-secondary-light tracking-wide mb-0">Pengaturan Cetak Invoice</p>
        </div>
        <form method="GET" action="{{ route('admin.invoice.show', $permintaan) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="form-label text-xs">Nama Perusahaan (Header)</label>
                <input type="text" name="company_name" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['company_name'] }}" placeholder="Contoh: CV. Binuangeun Indah">
            </div>
            <div class="md:col-span-1 lg:col-span-2">
                <label class="form-label text-xs">Alamat Perusahaan (Header)</label>
                <input type="text" name="company_address" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['company_address'] }}" placeholder="Contoh: Jl. Serang-Cilegon...">
            </div>
            <div>
                <label class="form-label text-xs">Tagihan Kepada - Nama</label>
                <input type="text" name="bill_to_name" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['bill_to_name'] }}" placeholder="Contoh: Ikna Wijaya">
            </div>
            <div>
                <label class="form-label text-xs">Tagihan Kepada - Telepon</label>
                <input type="text" name="bill_to_phone" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['bill_to_phone'] }}" placeholder="Contoh: 089610854904">
            </div>
            <div class="lg:col-span-2">
                <label class="form-label text-xs">Tagihan Kepada - Alamat</label>
                <input type="text" name="bill_to_address" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['bill_to_address'] }}" placeholder="Alamat pelanggan">
            </div>
            <div>
                <label class="form-label text-xs">No. PO Customer</label>
                <input type="text" name="no_po_customer" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['no_po_customer'] }}" placeholder="Terisi otomatis dari permintaan (No. PO Customer)">
            </div>
            <div>
                <label class="form-label text-xs">Tanggal Invoice</label>
                <input type="date" name="invoice_date" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['invoice_date'] }}">
            </div>
            <div>
                <label class="form-label text-xs">Bank</label>
                <input type="text" name="bank_name" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['bank_name'] }}" placeholder="Contoh: BCA / Mandiri">
            </div>
            <div>
                <label class="form-label text-xs">No. Rekening</label>
                <input type="text" name="bank_account_number" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['bank_account_number'] }}" placeholder="Contoh: 123-456-789">
            </div>
            <div>
                <label class="form-label text-xs">Atas Nama (Pembayaran)</label>
                <input type="text" name="bank_account_holder" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['bank_account_holder'] }}" placeholder="Contoh: CV. Binuangeun Indah">
            </div>
            <div>
                <label class="form-label text-xs">Nama Tanda Tangan Penerima</label>
                <input type="text" name="receiver_name" class="form-control form-control-sm" value="{{ $invoicePrintDefaults['receiver_name'] }}" placeholder="Contoh: Ikna Wijaya">
            </div>
            <div class="md:col-span-2 lg:col-span-3 flex flex-wrap gap-2 pt-2">
                <button type="submit" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium">
                    Terapkan ke Preview
                </button>
                <a href="{{ route('admin.invoice.show', $permintaan) }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100">
                    Reset Default
                </a>
                <a href="{{ route('admin.invoice.print', $permintaan) . '?' . http_build_query($invoicePrintDefaults) }}" target="_blank"
                    class="px-4 py-2 rounded-lg bg-neutral-800 hover:bg-neutral-900 text-white text-sm font-medium flex items-center gap-2">
                    <iconify-icon icon="ri:printer-line"></iconify-icon> Cetak dengan Pengaturan Ini
                </a>
            </div>
        </form>
    </div>

    {{-- Bill To & meta — lebar penuh --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 w-full">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-4">
            <div>
                <h6 class="text-xs font-bold uppercase text-secondary-light tracking-wide mb-1">Bill To</h6>
                <p class="font-bold text-lg dark:text-white mb-0">{{ $permintaan->pelanggan->name }}</p>
                @if($permintaan->pelanggan->phone ?? null)
                <p class="text-sm text-secondary-light mb-0">{{ $permintaan->pelanggan->phone }}</p>
                @endif
                @if($permintaan->pelanggan->address ?? null)
                <p class="text-sm text-secondary-light mb-0">{{ $permintaan->pelanggan->address }}</p>
                @endif
            </div>
            <div class="sm:text-right shrink-0">
                @php $color = ['siap_kirim'=>'primary','selesai'=>'success'][$permintaan->status] ?? 'secondary'; @endphp
                <span class="px-3 py-1.5 rounded-lg bg-{{ $color }}-100 text-{{ $color }}-700 text-sm font-bold">{{ $permintaan->status_label }}</span>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 text-sm pt-4 border-t border-neutral-200 dark:border-neutral-600">
            <div>
                <p class="text-secondary-light mb-1 text-xs">No. Permintaan</p>
                <p class="font-bold dark:text-white font-mono text-xs mb-0">{{ $permintaan->no_permintaan }}</p>
            </div>
            @if($permintaan->no_po_customer)
            <div>
                <p class="text-secondary-light mb-1 text-xs">No. PO Customer</p>
                <p class="font-bold dark:text-white font-mono text-xs mb-0">{{ $permintaan->no_po_customer }}</p>
            </div>
            @endif
            @if($permintaan->suratJalan)
            <div>
                <p class="text-secondary-light mb-1 text-xs">No. Surat Jalan</p>
                <p class="font-bold text-info-600 font-mono text-xs mb-0">{{ $permintaan->suratJalan->no_sj }}</p>
            </div>
            @endif
            <div>
                <p class="text-secondary-light mb-1 text-xs">Tgl. Permintaan</p>
                <p class="font-semibold dark:text-white text-xs mb-0">{{ $permintaan->tanggal_request->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-secondary-light mb-1 text-xs">Diproses Oleh</p>
                <p class="font-semibold dark:text-white text-xs mb-0">{{ $permintaan->diprosesOleh->name ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Ringkasan — lebar penuh --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 w-full">
        <p class="text-xs font-bold uppercase text-secondary-light tracking-wide mb-3">Ringkasan</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm max-w-4xl">
            <div class="flex justify-between sm:flex-col sm:justify-start gap-1 sm:gap-0 py-2 sm:py-0 border-b sm:border-b-0 border-neutral-200 dark:border-neutral-600">
                <span class="text-secondary-light">Subtotal</span>
                <span class="font-semibold dark:text-white">Rp {{ number_format($subtotal,0,',','.') }}</span>
            </div>
            <div class="flex justify-between sm:flex-col sm:justify-start gap-1 sm:gap-0 py-2 sm:py-0 border-b sm:border-b-0 border-neutral-200 dark:border-neutral-600">
                <span class="text-secondary-light">Diskon</span>
                <span class="font-semibold dark:text-white">Rp 0</span>
            </div>
            <div class="flex justify-between sm:flex-col sm:justify-start gap-1 sm:gap-0 py-2 sm:py-0 border-b sm:border-b-0 border-neutral-200 dark:border-neutral-600">
                <span class="text-secondary-light">Pajak</span>
                <span class="font-semibold dark:text-white">—</span>
            </div>
            <div class="flex justify-between sm:flex-col sm:justify-start gap-1 sm:gap-0 py-2 sm:py-0 sm:pl-4 sm:border-l border-neutral-200 dark:border-neutral-600">
                <span class="font-bold dark:text-white">Total</span>
                <span class="font-bold text-success-600 text-base">Rp {{ number_format($subtotal,0,',','.') }}</span>
            </div>
        </div>
    </div>

    @if($permintaan->catatan)
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 w-full">
        <p class="text-xs font-bold uppercase text-secondary-light tracking-wide mb-2">Catatan</p>
        <p class="text-sm dark:text-white mb-0">{{ $permintaan->catatan }}</p>
    </div>
    @endif

    {{-- Tabel barang — lebar penuh --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Rincian Barang</h6>
        </div>
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-0 text-sm">
                <thead>
                    <tr class="bg-neutral-50 dark:bg-neutral-800">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase w-12">#</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Barang</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Qty</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Harga Satuan</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Subtotal</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Koreksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                    @php $total = 0; $no = 1; @endphp
                    @foreach($permintaan->detail->where('is_checked', true) as $d)
                    @php $total += $d->subtotal_jual; @endphp
                    @php $formId = 'invoice-item-form-' . $d->id; @endphp
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600/40">
                        <td class="px-5 py-3 text-secondary-light">{{ $no++ }}</td>
                        <td class="px-5 py-3">
                            <p class="font-semibold dark:text-white mb-0 text-sm">{{ $d->barang->nama }}</p>
                            <p class="text-xs text-secondary-light mb-0">{{ $d->barang->kode }}</p>
                        </td>
                        <td class="px-5 py-3 text-right text-sm dark:text-white whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <input type="number" name="jumlah_disetujui" form="{{ $formId }}" class="form-control form-control-sm text-right w-28" min="0.01" step="0.01" value="{{ $d->jumlah_disetujui }}">
                                <span class="text-secondary-light">{{ $d->barang->satuan }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-right text-sm dark:text-white whitespace-nowrap">
                            <input type="number" name="harga_jual" form="{{ $formId }}" class="form-control form-control-sm text-right w-32 ml-auto" min="0" step="1" value="{{ $d->harga_jual }}">
                        </td>
                        <td class="px-5 py-3 text-right font-bold text-success-600 whitespace-nowrap">Rp {{ number_format($d->subtotal_jual,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <form id="{{ $formId }}" method="POST" action="{{ route('admin.invoice.items.update', [$permintaan, $d]) }}" onsubmit="return confirm('Simpan koreksi item ini? Stok, surat jalan, subtotal, dan total invoice akan disesuaikan.');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-warning-600 hover:bg-warning-700 text-white text-xs font-semibold">
                                    Simpan
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-neutral-50 dark:bg-neutral-800">
                        <td colspan="4" class="px-5 py-4 text-right text-sm font-bold dark:text-white">Total</td>
                        <td class="px-5 py-4 text-right font-bold text-success-600 text-xl whitespace-nowrap">Rp {{ number_format($total,0,',','.') }}</td>
                        <td class="px-5 py-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
