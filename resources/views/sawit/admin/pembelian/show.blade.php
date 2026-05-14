@extends('layouts.sawit')
@section('title', 'Detail Pembelian')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div><h6 class="font-semibold mb-0 dark:text-white">Detail Pembelian: {{ $pembelian->no_pembelian }}</h6></div>
    <div class="flex items-center gap-2">
        <a href="{{ route('sawit.admin.pembelian.edit', $pembelian) }}" class="px-4 py-2 rounded-lg bg-warning-600 hover:bg-warning-700 text-white text-sm font-medium transition flex items-center gap-2"><iconify-icon icon="ri:edit-line"></iconify-icon> Edit</a>
        <a href="{{ route('sawit.admin.pembelian.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition flex items-center gap-2"><iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali</a>
    </div>
</div>

@include('partials.alert')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center justify-between">
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Informasi Pembelian</h6>
                <span class="text-xs px-3 py-1 rounded font-semibold {{ $pembelian->status==='done'?'bg-success-100 text-success-600':'bg-warning-100 text-warning-600' }}">{{ strtoupper($pembelian->status) }}</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-5 gap-x-4">
                    <div><p class="text-xs text-secondary-light mb-1">No Pembelian</p><p class="text-sm font-semibold dark:text-white">{{ $pembelian->no_pembelian }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Tanggal</p><p class="text-sm font-semibold dark:text-white">{{ $pembelian->tanggal->format('d/m/Y') }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Nama Pembeli/Penjual</p><p class="text-sm font-semibold dark:text-white">{{ $pembelian->nama_penjual }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Jenis Sawit</p><p class="text-sm font-semibold dark:text-white">{{ $pembelian->barang->nama_sawit }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">QTY Timbangan</p><p class="text-sm font-semibold dark:text-white">{{ number_format($pembelian->qty_timbangan, 2) }} Kg</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Refaksi</p><p class="text-sm font-semibold dark:text-white">{{ number_format($pembelian->refaksi, 2) }} Kg</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">QTY Setelah Refaksi</p><p class="text-sm font-bold text-success-600">{{ number_format($pembelian->qty_setelah_refaksi, 2) }} Kg</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Harga per Kg</p><p class="text-sm font-semibold dark:text-white">Rp {{ number_format($pembelian->harga_per_kg, 0, ',', '.') }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Total Harga</p><p class="text-lg font-bold text-primary-600">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</p></div>
                </div>
            </div>
        </div>

        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Pembayaran</h6>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-5 gap-x-4">
                    <div><p class="text-xs text-secondary-light mb-1">Type Payment</p><p class="text-sm font-semibold dark:text-white">{{ strtoupper($pembelian->type_payment) }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Potongan DP</p><p class="text-sm font-semibold dark:text-white">Rp {{ number_format($pembelian->potongan_dp, 0, ',', '.') }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">DP Sebelumnya</p><p class="text-sm font-semibold dark:text-white">Rp {{ number_format($pembelian->dp_sebelumnya, 0, ',', '.') }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Tanggal Transfer</p><p class="text-sm font-semibold dark:text-white">{{ $pembelian->tanggal_transfer ? $pembelian->tanggal_transfer->format('d/m/Y') : '-' }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Selisih (Total - DP)</p><p class="text-sm font-bold text-primary-600">Rp {{ number_format($pembelian->selisih, 0, ',', '.') }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Sisa DP</p><p class="text-sm font-bold {{ $pembelian->sisa_dp <= 0 ? 'text-success-600' : 'text-danger-600' }}">Rp {{ number_format($pembelian->sisa_dp, 0, ',', '.') }}</p></div>
                </div>
            </div>
        </div>
    </div>

    <div>
        @if($pembelian->keterangan)
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Keterangan</h6>
            </div>
            <div class="p-6"><p class="text-sm dark:text-white">{{ $pembelian->keterangan }}</p></div>
        </div>
        @endif

        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Info Tambahan</h6>
            </div>
            <div class="p-6 space-y-3">
                <div><p class="text-xs text-secondary-light mb-1">Dibuat Oleh</p><p class="text-sm dark:text-white">{{ $pembelian->creator->name ?? '-' }}</p></div>
                <div><p class="text-xs text-secondary-light mb-1">Dibuat Pada</p><p class="text-sm dark:text-white">{{ $pembelian->created_at->format('d/m/Y H:i') }}</p></div>
                @if($pembelian->updater)
                <div><p class="text-xs text-secondary-light mb-1">Diupdate Oleh</p><p class="text-sm dark:text-white">{{ $pembelian->updater->name }}</p></div>
                <div><p class="text-xs text-secondary-light mb-1">Diupdate Pada</p><p class="text-sm dark:text-white">{{ $pembelian->updated_at->format('d/m/Y H:i') }}</p></div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
