@extends('layouts.sawit')
@section('title', 'Detail Penjualan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Detail Penjualan: {{ $penjualan->no_invoice }}</h6>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('sawit.admin.penjualan.print', $penjualan) }}" target="_blank" class="px-4 py-2 rounded-lg border border-neutral-300 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition flex items-center gap-2"><iconify-icon icon="ri:printer-line"></iconify-icon> Cetak</a>
        <a href="{{ route('sawit.admin.penjualan.edit', $penjualan) }}" class="px-4 py-2 rounded-lg bg-warning-600 hover:bg-warning-700 text-white text-sm font-medium transition flex items-center gap-2"><iconify-icon icon="ri:edit-line"></iconify-icon> Edit</a>
        @if(!$penjualan->suratJalan)
        <a href="{{ route('sawit.admin.surat-jalan.create', ['penjualan_id' => $penjualan->id]) }}" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition flex items-center gap-2"><iconify-icon icon="ri:truck-line"></iconify-icon> Buat Surat Jalan</a>
        @endif
    </div>
</div>

@include('partials.alert')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Info Utama --}}
    <div class="lg:col-span-2">
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center justify-between">
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Informasi Penjualan</h6>
                <span class="text-xs px-3 py-1 rounded font-semibold {{ $penjualan->status==='done'?'bg-success-100 text-success-600':'bg-warning-100 text-warning-600' }}">{{ strtoupper($penjualan->status) }}</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-5 gap-x-4">
                    <div><p class="text-xs text-secondary-light mb-1">No Invoice</p><p class="text-sm font-semibold dark:text-white">{{ $penjualan->no_invoice }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Tanggal</p><p class="text-sm font-semibold dark:text-white">{{ $penjualan->tanggal->format('d/m/Y') }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Perusahaan</p><p class="text-sm font-semibold dark:text-white">{{ $penjualan->perusahaan->nama_perusahaan }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Jenis Sawit</p><p class="text-sm font-semibold dark:text-white">{{ $penjualan->barang->nama_sawit }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">QTY Timbangan</p><p class="text-sm font-semibold dark:text-white">{{ number_format($penjualan->qty_timbangan, 2) }} Kg</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Refaksi</p><p class="text-sm font-semibold dark:text-white">{{ number_format($penjualan->refaksi, 2) }} Kg</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Total Kg Setelah Refaksi</p><p class="text-sm font-bold text-success-600">{{ number_format($penjualan->total_kg_setelah_refaksi, 2) }} Kg</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Harga per Kg</p><p class="text-sm font-semibold dark:text-white">Rp {{ number_format($penjualan->harga_per_kg, 0, ',', '.') }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Total Harga</p><p class="text-lg font-bold text-primary-600">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</p></div>
                </div>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Pembayaran</h6>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-y-5 gap-x-4">
                    <div><p class="text-xs text-secondary-light mb-1">Tanggal Bayar</p><p class="text-sm font-semibold dark:text-white">{{ $penjualan->tanggal_bayar ? $penjualan->tanggal_bayar->format('d/m/Y') : '-' }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Pembayaran Invoice</p><p class="text-sm font-semibold dark:text-white">Rp {{ number_format($penjualan->pembayaran_invoice, 0, ',', '.') }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Potongan</p><p class="text-sm font-semibold dark:text-white">Rp {{ number_format($penjualan->potongan, 0, ',', '.') }}</p></div>
                    <div><p class="text-xs text-secondary-light mb-1">Selisih Pembayaran</p><p class="text-sm font-bold {{ $penjualan->selisih_pembayaran <= 0 ? 'text-success-600' : 'text-danger-600' }}">Rp {{ number_format($penjualan->selisih_pembayaran, 0, ',', '.') }}</p></div>
                    <div class="col-span-2"><p class="text-xs text-secondary-light mb-1">Keterangan Potongan</p><p class="text-sm dark:text-white">{{ $penjualan->keterangan_potongan ?: '-' }}</p></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div>
        {{-- Kendaraan --}}
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Kendaraan</h6>
            </div>
            <div class="p-6 space-y-4">
                <div><p class="text-xs text-secondary-light mb-1">Jenis Kendaraan</p><p class="text-sm font-semibold dark:text-white">{{ $penjualan->jenis_kendaraan ?: '-' }}</p></div>
                <div><p class="text-xs text-secondary-light mb-1">No Mobil / Plat</p><p class="text-sm font-semibold dark:text-white">{{ $penjualan->no_mobil ?: '-' }}</p></div>
                <div><p class="text-xs text-secondary-light mb-1">Nama Supir</p><p class="text-sm font-semibold dark:text-white">{{ $penjualan->nama_supir ?: '-' }}</p></div>
            </div>
        </div>

        {{-- Surat Jalan --}}
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Surat Jalan</h6>
            </div>
            <div class="p-6">
                @if($penjualan->suratJalan)
                <p class="text-sm font-semibold dark:text-white mb-2">{{ $penjualan->suratJalan->no_surat_jalan }}</p>
                <a href="{{ route('sawit.admin.surat-jalan.show', $penjualan->suratJalan) }}" class="text-xs text-primary-600 hover:underline">Lihat Surat Jalan</a>
                @else
                <p class="text-sm text-secondary-light">Belum ada surat jalan</p>
                <a href="{{ route('sawit.admin.surat-jalan.create', ['penjualan_id' => $penjualan->id]) }}" class="mt-2 inline-block text-xs text-primary-600 hover:underline">+ Buat Surat Jalan</a>
                @endif
            </div>
        </div>

        {{-- Keterangan --}}
        @if($penjualan->keterangan)
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Keterangan</h6>
            </div>
            <div class="p-6"><p class="text-sm dark:text-white">{{ $penjualan->keterangan }}</p></div>
        </div>
        @endif
    </div>
</div>
@endsection
