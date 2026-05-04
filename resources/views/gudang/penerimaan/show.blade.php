@extends('layouts.app')
@section('title', 'Detail Penerimaan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">{{ $penerimaan->no_penerimaan }}</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Detail Penerimaan Barang</p>
    </div>
    <a href="{{ route('gudang.penerimaan.index') }}"
        class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-1.5">
        <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali
    </a>
</div>

{{-- Info Bar --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">No. Penerimaan</p>
        <p class="font-bold text-success-600 text-sm mb-0">{{ $penerimaan->no_penerimaan }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Referensi PO</p>
        <p class="font-semibold text-primary-600 text-sm mb-0">{{ $penerimaan->pembelian->no_po }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Supplier</p>
        <p class="font-semibold text-sm dark:text-white mb-0">{{ $penerimaan->pembelian->supplier->name }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Tanggal</p>
        <p class="font-medium text-sm dark:text-white mb-0">{{ $penerimaan->tanggal->format('d M Y') }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Diterima Oleh</p>
        <p class="font-medium text-sm dark:text-white mb-0">{{ $penerimaan->diterima->name }}</p>
    </div>
</div>

@if($penerimaan->catatan)
<div class="mb-6 p-4 rounded-xl border border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-start gap-3">
    <iconify-icon icon="ri:sticky-note-line" class="text-neutral-500 text-lg flex-shrink-0"></iconify-icon>
    <p class="text-sm text-neutral-700 dark:text-neutral-200 mb-0"><strong>Catatan:</strong> {{ $penerimaan->catatan }}</p>
</div>
@endif

{{-- Barang Diterima Table --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <iconify-icon icon="ri:inbox-archive-line" class="text-success-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Barang yang Diterima</h6>
        </div>
        <span class="font-bold text-success-600">Total: Rp {{ number_format($penerimaan->detail->sum('subtotal'), 0, ',', '.') }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Barang</th>
                    <th class="text-right px-6 py-3 text-xs font-semiold text-secondary-light uppercase">Jumlah Diterima</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Harga Satuan</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @foreach($penerimaan->detail as $d)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                    <td class="px-6 py-3.5">
                        <p class="font-semibold text-sm dark:text-white mb-0">{{ $d->barang->nama }}</p>
                        <p class="text-xs text-secondary-light mb-0">{{ $d->barang->kode }}</p>
                    </td>
                    <td class="px-6 py-3.5 text-right text-sm dark:text-white font-semibold">{{ format_qty_id($d->jumlah_diterima) }} {{ $d->barang->satuan }}</td>
                    <td class="px-6 py-3.5 text-right text-sm text-secondary-light">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-6 py-3.5 text-right text-sm font-bold dark:text-white">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <td colspan="3" class="px-6 py-3 text-right font-bold text-sm dark:text-white">Total Nilai</td>
                    <td class="px-6 py-3 text-right font-bold text-success-600 text-base">Rp {{ number_format($penerimaan->detail->sum('subtotal'), 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
