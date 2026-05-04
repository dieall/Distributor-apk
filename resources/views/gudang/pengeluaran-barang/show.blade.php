@extends('layouts.app')
@section('title', $pb->no_pengeluaran)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">{{ $pb->no_pengeluaran }}</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Detail pengeluaran barang</p>
    </div>
    <a href="{{ route('gudang.pengeluaran-barang.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 flex items-center gap-2">
        <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light mb-1">Tanggal</p>
        <p class="font-semibold dark:text-white mb-0">{{ $pb->tanggal->format('d F Y') }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light mb-1">Alasan</p>
        <p class="font-semibold dark:text-white mb-0">{{ $pb->alasan_label }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light mb-1">Dicatat oleh</p>
        <p class="font-semibold dark:text-white mb-0">{{ $pb->dibuatOleh->name ?? '—' }}</p>
    </div>
</div>

@if($pb->catatan)
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 mb-6">
    <p class="text-xs font-semibold text-secondary-light uppercase mb-1">Catatan</p>
    <p class="text-sm dark:text-white mb-0">{{ $pb->catatan }}</p>
</div>
@endif

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Barang yang dikeluarkan</h6>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Barang</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Jumlah</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @foreach($pb->detail as $d)
                <tr>
                    <td class="px-5 py-3">
                        <p class="font-semibold dark:text-white mb-0">{{ $d->barang->nama }}</p>
                        <p class="text-xs text-secondary-light mb-0">{{ $d->barang->kode }}</p>
                    </td>
                    <td class="px-5 py-3 text-right font-bold text-danger-600 whitespace-nowrap">
                        {{ format_qty_id($d->jumlah_keluar) }} {{ $d->barang->satuan }}
                    </td>
                    <td class="px-5 py-3 text-secondary-light text-xs">{{ $d->keterangan ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<p class="text-xs text-secondary-light mt-4 mb-0">Mutasi stok tercatat dengan referensi <strong class="text-primary-600">{{ $pb->no_pengeluaran }}</strong> di riwayat masing-masing barang.</p>
@endsection
