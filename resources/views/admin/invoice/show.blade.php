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
        <a href="{{ route('admin.surat-jalan.show', $permintaan->suratJalan) }}"
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
@endphp

<div class="w-full space-y-6">
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
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                    @php $total = 0; $no = 1; @endphp
                    @foreach($permintaan->detail->where('is_checked', true) as $d)
                    @php $total += $d->subtotal_jual; @endphp
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600/40">
                        <td class="px-5 py-3 text-secondary-light">{{ $no++ }}</td>
                        <td class="px-5 py-3">
                            <p class="font-semibold dark:text-white mb-0 text-sm">{{ $d->barang->nama }}</p>
                            <p class="text-xs text-secondary-light mb-0">{{ $d->barang->kode }}</p>
                        </td>
                        <td class="px-5 py-3 text-right text-sm dark:text-white whitespace-nowrap">{{ number_format($d->jumlah_disetujui,0,',','.') }} {{ $d->barang->satuan }}</td>
                        <td class="px-5 py-3 text-right text-sm dark:text-white whitespace-nowrap">Rp {{ number_format($d->harga_jual,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right font-bold text-success-600 whitespace-nowrap">Rp {{ number_format($d->subtotal_jual,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-neutral-50 dark:bg-neutral-800">
                        <td colspan="4" class="px-5 py-4 text-right text-sm font-bold dark:text-white">Total</td>
                        <td class="px-5 py-4 text-right font-bold text-success-600 text-xl whitespace-nowrap">Rp {{ number_format($total,0,',','.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
