@extends('layouts.app')
@section('title', 'Detail Permintaan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">{{ $permintaan->no_permintaan }}</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Status Permintaan Barang</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="px-3 py-1.5 rounded-lg bg-{{ $permintaan->status_color }}-100 text-{{ $permintaan->status_color }}-600 text-sm font-semibold">
            {{ $permintaan->status_label }}
        </span>
        <a href="{{ route('pelanggan.permintaan.index') }}"
            class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-1.5">
            <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali
        </a>
    </div>
</div>

{{-- Info Bar --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">No. Permintaan</p>
        <p class="font-bold text-primary-600 text-sm mb-0">{{ $permintaan->no_permintaan }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Tanggal Request</p>
        <p class="font-medium text-sm dark:text-white mb-0">{{ $permintaan->tanggal_request->format('d M Y') }}</p>
    </div>
    @if($permintaan->no_po_customer)
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">No. PO Customer</p>
        <p class="font-bold text-primary-600 text-sm font-mono mb-0">{{ $permintaan->no_po_customer }}</p>
    </div>
    @endif
    @if($permintaan->tanggal_dibutuhkan)
    <div class="card shadow-none border border-warning-200 bg-warning-50 dark:bg-neutral-700 dark:border-neutral-600 rounded-xl p-4">
        <p class="text-xs text-warning-600 font-medium mb-1">Dibutuhkan Sebelum</p>
        <p class="font-bold text-warning-600 text-sm mb-0">{{ $permintaan->tanggal_dibutuhkan->format('d M Y') }}</p>
    </div>
    @endif
    @if($permintaan->diprosesOleh)
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Diproses Oleh</p>
        <p class="font-medium text-sm dark:text-white mb-0">{{ $permintaan->diprosesOleh->name }}</p>
    </div>
    @endif
</div>

@if($permintaan->catatan)
<div class="mb-6 p-4 rounded-xl border border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-start gap-3">
    <iconify-icon icon="ri:sticky-note-line" class="text-neutral-500 text-lg flex-shrink-0"></iconify-icon>
    <p class="text-sm text-neutral-700 dark:text-neutral-200 mb-0"><strong>Catatan:</strong> {{ $permintaan->catatan }}</p>
</div>
@endif

{{-- Tracking Status --}}
@php
    $steps = ['pending' => 'Menunggu Proses', 'diproses' => 'Sedang Diproses', 'siap_kirim' => 'Siap Dikirim', 'selesai' => 'Selesai'];
    $order = array_keys($steps);
    $current = array_search($permintaan->status, $order);
@endphp
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 mb-6">
    <h6 class="font-semibold text-sm dark:text-white mb-4">Tracking Status</h6>
    <div class="flex items-center gap-2 flex-wrap">
        @foreach($steps as $key => $label)
        @php $idx = array_search($key, $order); $done = $current !== false && $idx <= $current; @endphp
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-2 px-3 py-2 rounded-lg {{ $done ? 'bg-success-100 text-success-700' : 'bg-neutral-100 dark:bg-neutral-600 text-secondary-light' }}">
                <iconify-icon icon="{{ $done ? 'ri:checkbox-circle-fill' : 'ri:radio-button-line' }}" class="text-base"></iconify-icon>
                <span class="text-xs font-medium">{{ $label }}</span>
            </div>
            @if(!$loop->last)
            <iconify-icon icon="ri:arrow-right-line" class="text-neutral-300 text-sm"></iconify-icon>
            @endif
        </div>
        @endforeach
    </div>

    @if($permintaan->suratJalan)
    <div class="mt-5 pt-4 border-t border-neutral-200 dark:border-neutral-600 grid grid-cols-3 sm:grid-cols-4 gap-4">
        <div>
            <p class="text-xs text-secondary-light font-medium mb-1">No. Surat Jalan</p>
            <p class="font-bold text-success-600 text-sm mb-0">{{ $permintaan->suratJalan->no_sj }}</p>
        </div>
        @if($permintaan->suratJalan->driver)
        <div>
            <p class="text-xs text-secondary-light font-medium mb-1">Driver</p>
            <p class="font-medium text-sm dark:text-white mb-0">{{ $permintaan->suratJalan->driver }}</p>
        </div>
        @endif
        @if($permintaan->suratJalan->no_kendaraan)
        <div>
            <p class="text-xs text-secondary-light font-medium mb-1">Kendaraan</p>
            <p class="font-bold text-sm dark:text-white mb-0">{{ $permintaan->suratJalan->no_kendaraan }}</p>
        </div>
        @endif
    </div>
    @endif
</div>

{{-- Daftar Barang --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:list-check" class="text-primary-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Daftar Barang</h6>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Barang</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Diminta</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Disetujui</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @foreach($permintaan->detail as $d)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                    <td class="px-6 py-3.5">
                        <p class="font-semibold text-sm dark:text-white mb-0">{{ $d->barang->nama }}</p>
                        <p class="text-xs text-secondary-light mb-0">{{ $d->barang->kategori }} · {{ $d->barang->satuan }}</p>
                    </td>
                    <td class="px-6 py-3.5 text-right text-sm dark:text-white">{{ format_qty_id($d->jumlah_diminta) }} {{ $d->barang->satuan }}</td>
                    <td class="px-6 py-3.5 text-right text-sm font-bold dark:text-white">
                        @if($permintaan->status !== 'pending')
                        {{ format_qty_id($d->jumlah_disetujui) }} {{ $d->barang->satuan }}
                        @else
                        <span class="text-secondary-light">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        @if($permintaan->status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-warning-100 text-warning-600 text-xs font-semibold">Menunggu</span>
                        @elseif($d->is_checked)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-success-100 text-success-600 text-xs font-semibold">✓ Disiapkan</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-100 text-red-500 text-xs font-semibold">Tdk Tersedia</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
