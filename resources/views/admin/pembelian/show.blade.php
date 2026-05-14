@extends('layouts.app')
@section('title', 'Detail PO - ' . $pembelian->no_po)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">{{ $pembelian->no_po }}</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Detail Purchase Order</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="px-3 py-1.5 rounded-lg bg-{{ $pembelian->status_color }}-100 text-{{ $pembelian->status_color }}-600 text-sm font-semibold">
            {{ $pembelian->status_label }}
        </span>
        <a href="{{ fin_route('pembelian.index') }}"
            class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-1.5">
            <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali
        </a>
    </div>
</div>

@include('partials.alert')

{{-- Info Bar --}}
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">No. PO</p>
        <p class="font-bold text-primary-600 text-sm mb-0">{{ $pembelian->no_po }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Supplier</p>
        <p class="font-semibold text-sm dark:text-white mb-0">{{ $pembelian->supplier->name }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Tanggal PO</p>
        <p class="font-medium text-sm dark:text-white mb-0">{{ $pembelian->tanggal->format('d M Y') }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Estimasi Tiba</p>
        <p class="font-medium text-sm dark:text-white mb-0">{{ $pembelian->tanggal_kirim_estimasi?->format('d M Y') ?? '—' }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Dibuat Oleh</p>
        <p class="font-medium text-sm dark:text-white mb-0">{{ $pembelian->dibuatOleh->name }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Total</p>
        <p class="font-bold text-lg text-primary-600 mb-0">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</p>
    </div>
</div>

@if($pembelian->catatan)
<div class="mb-6 p-4 rounded-xl border border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-start gap-3">
    <iconify-icon icon="ri:sticky-note-line" class="text-neutral-500 text-lg flex-shrink-0 mt-0.5"></iconify-icon>
    <p class="text-sm text-neutral-700 dark:text-neutral-200 mb-0"><strong class="font-semibold">Catatan:</strong> {{ $pembelian->catatan }}</p>
</div>
@endif

@if($pembelian->bukti_pembayaran && count($pembelian->bukti_pembayaran) > 0)
<div class="mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
            <iconify-icon icon="ri:image-line" class="text-warning-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Bukti Pembayaran ({{ count($pembelian->bukti_pembayaran) }} File)</h6>
        </div>
        <div class="p-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($pembelian->bukti_pembayaran as $bukti)
            @php
                $rawPath = trim((string) $bukti);
                if (filter_var($rawPath, FILTER_VALIDATE_URL)) {
                    $urlPath = parse_url($rawPath, PHP_URL_PATH) ?? '';
                    $normalizedPath = ltrim((string) $urlPath, '/');
                } else {
                    $normalizedPath = ltrim($rawPath, '/');
                }

                $normalizedPath = str_replace('\\', '/', $normalizedPath);
                if (str_starts_with($normalizedPath, 'storage/')) {
                    $normalizedPath = substr($normalizedPath, 8);
                }
                if (str_starts_with($normalizedPath, 'public/')) {
                    $normalizedPath = substr($normalizedPath, 7);
                }
                $fileUrl = fin_route('pembelian.bukti', ['path' => $normalizedPath]);
                $ext = pathinfo($normalizedPath, PATHINFO_EXTENSION);
            @endphp
            @if(in_array(strtolower($ext), ['jpg','jpeg','png','webp']))
            <a href="{{ $fileUrl }}" target="_blank" class="block rounded-lg overflow-hidden border border-neutral-200 hover:opacity-90 transition">
                <img src="{{ $fileUrl }}" alt="Bukti Pembayaran"
                    class="w-full h-32 object-cover">
            </a>
            @else
            <a href="{{ $fileUrl }}" target="_blank"
                class="flex flex-col items-center justify-center gap-2 h-32 rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 transition text-center p-2">
                <iconify-icon icon="ri:file-pdf-line" class="text-red-500 text-3xl"></iconify-icon>
                <p class="font-semibold text-xs text-neutral-800 mb-0 truncate w-full px-2" title="{{ basename($normalizedPath) }}">{{ basename($normalizedPath) }}</p>
            </a>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Detail Barang --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:shopping-cart-2-line" class="text-primary-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Detail Barang yang Dipesan</h6>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Barang</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Jumlah</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Harga Satuan</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @foreach($pembelian->detail as $d)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                    <td class="px-6 py-3.5">
                        <p class="font-semibold text-sm dark:text-white mb-0">{{ $d->barang->nama }}</p>
                        <p class="text-xs text-secondary-light mb-0">{{ $d->barang->kode }}</p>
                    </td>
                    <td class="px-6 py-3.5 text-right text-sm dark:text-white">{{ format_qty_id($d->jumlah) }} {{ $d->barang->satuan }}</td>
                    <td class="px-6 py-3.5 text-right text-sm text-secondary-light">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-6 py-3.5 text-right font-bold text-sm dark:text-white">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <td colspan="3" class="px-6 py-3 text-right font-bold text-sm dark:text-white">TOTAL</td>
                    <td class="px-6 py-3 text-right font-bold text-primary-600 text-base">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Update Status --}}
    @if(!in_array($pembelian->status, ['diterima','dibatalkan']) && !auth()->user()->isDirektur())
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
            <iconify-icon icon="ri:refresh-line" class="text-warning-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Update Status</h6>
        </div>
        <div class="p-6">
            <form action="{{ fin_route('pembelian.status', $pembelian) }}" method="POST" class="flex items-end gap-3">
                @csrf @method('PATCH')
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Status Baru</label>
                    <select name="status" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none">
                        @foreach(['draft','dikirim','sebagian_diterima','diterima','dibatalkan'] as $s)
                        @if($s !== $pembelian->status)
                        <option value="{{ $s }}">{{ ucwords(str_replace('_',' ',$s)) }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-warning-600 hover:bg-warning-700 text-white text-sm font-medium flex items-center gap-2 transition">
                    <iconify-icon icon="ri:refresh-line"></iconify-icon> Update
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Riwayat Penerimaan --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
            <iconify-icon icon="ri:inbox-archive-line" class="text-success-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Riwayat Penerimaan</h6>
        </div>
        <div class="p-6">
            @forelse($pembelian->penerimaan as $pen)
            <div class="flex items-center gap-3 {{ !$loop->last ? 'mb-4 pb-4 border-b border-neutral-100 dark:border-neutral-600' : '' }}">
                <div class="w-8 h-8 bg-success-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:checkbox-circle-line" class="text-success-600 text-sm"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold text-sm dark:text-white mb-0">{{ $pen->no_penerimaan }}</p>
                    <p class="text-xs text-secondary-light mb-0">{{ $pen->tanggal->format('d M Y') }} · {{ $pen->diterima->name }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-secondary-light text-center mb-0">Belum ada penerimaan</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
