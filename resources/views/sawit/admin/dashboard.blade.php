@extends('layouts.sawit')
@section('title', 'Dashboard Admin Sawit')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Dashboard Admin Sawit</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Selamat datang di sistem manajemen sawit</p>
    </div>
    <div class="flex items-center gap-2">
        <iconify-icon icon="ri:plant-line" class="text-success-600 text-2xl"></iconify-icon>
        <span class="text-sm text-neutral-600 dark:text-neutral-300">{{ now()->format('d F Y') }}</span>
    </div>
</div>

@include('partials.alert')

{{-- Stats Cards Row 1 --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:box-3-line" class="text-primary-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Barang</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($stats['total_barang']) }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:scales-3-line" class="text-success-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Stok</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($stats['total_stok_kg'], 2) }} Kg</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-info-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:shopping-cart-line" class="text-info-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Penjualan Hari Ini</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($stats['penjualan_hari_ini']) }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-warning-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:inbox-archive-line" class="text-warning-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Pembelian Hari Ini</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($stats['pembelian_hari_ini']) }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-danger-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:alert-line" class="text-danger-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Belum Lunas</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($stats['total_belum_lunas']) }}</h6>
            </div>
        </div>
    </div>
</div>

{{-- Stats Cards Row 2 --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:money-dollar-circle-line" class="text-success-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Pendapatan</p>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-warning-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:wallet-3-line" class="text-warning-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Pembelian</p>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Rp {{ number_format($stats['total_biaya_pembelian'], 0, ',', '.') }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-info-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:draft-line" class="text-info-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Invoice Draft</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($stats['total_invoice_draft']) }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:checkbox-circle-line" class="text-primary-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Invoice Done</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($stats['total_invoice_done']) }}</h6>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Aksi Cepat</h6>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('sawit.admin.penjualan.create') }}" class="flex items-center gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-600 hover:border-success-500 hover:bg-success-50 dark:hover:bg-success-900/20 transition">
                <div class="w-10 h-10 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:shopping-cart-line" class="text-success-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">Penjualan Baru</p>
                    <p class="text-xs text-secondary-light mb-0">Input penjualan sawit</p>
                </div>
            </a>

            <a href="{{ route('sawit.admin.pembelian.create') }}" class="flex items-center gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-600 hover:border-warning-500 hover:bg-warning-50 dark:hover:bg-warning-900/20 transition">
                <div class="w-10 h-10 rounded-lg bg-warning-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:inbox-archive-line" class="text-warning-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">Pembelian Baru</p>
                    <p class="text-xs text-secondary-light mb-0">Input pembelian sawit</p>
                </div>
            </a>

            <a href="{{ route('sawit.admin.surat-jalan.create') }}" class="flex items-center gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-600 hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition">
                <div class="w-10 h-10 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:truck-line" class="text-primary-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">Surat Jalan</p>
                    <p class="text-xs text-secondary-light mb-0">Buat surat jalan</p>
                </div>
            </a>

            <a href="{{ route('sawit.admin.barang.index') }}" class="flex items-center gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-600 hover:border-info-500 hover:bg-info-50 dark:hover:bg-info-900/20 transition">
                <div class="w-10 h-10 rounded-lg bg-info-100 flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:box-3-line" class="text-info-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">Data Barang</p>
                    <p class="text-xs text-secondary-light mb-0">Kelola barang sawit</p>
                </div>
            </a>
        </div>
    </div>
</div>

{{-- Stok & Transaksi --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Stok Per Jenis --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Stok Per Jenis Sawit</h6>
        </div>
        <div class="p-6">
            @forelse($stokPerJenis as $item)
            <div class="flex items-center justify-between py-3 border-b border-neutral-100 dark:border-neutral-600 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
                        <iconify-icon icon="ri:plant-line" class="text-success-600 text-xl"></iconify-icon>
                    </div>
                    <div>
                        <p class="font-semibold mb-0 dark:text-white text-sm">{{ $item->nama_sawit }}</p>
                        <p class="text-xs text-secondary-light mb-0">{{ $item->id_sawit }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold mb-0 dark:text-white">{{ number_format($item->total_kg, 2) }} Kg</p>
                    <p class="text-xs {{ $item->total_kg > 0 ? 'text-success-600' : 'text-danger-600' }} mb-0">
                        {{ $item->total_kg > 0 ? 'Tersedia' : 'Kosong' }}
                    </p>
                </div>
            </div>
            @empty
            <p class="text-center text-secondary-light py-4">Belum ada data barang</p>
            @endforelse
        </div>
    </div>

    {{-- Penjualan Terbaru --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center justify-between">
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Penjualan Terbaru</h6>
            <a href="{{ route('sawit.admin.penjualan.index') }}" class="text-xs text-primary-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="p-6">
            @forelse($penjualanTerbaru as $item)
            <div class="flex items-center justify-between py-3 border-b border-neutral-100 dark:border-neutral-600 last:border-0">
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">{{ $item->no_invoice }}</p>
                    <p class="text-xs text-secondary-light mb-0">{{ $item->perusahaan->nama_perusahaan }}</p>
                    <p class="text-xs text-secondary-light mb-0">{{ $item->tanggal->format('d/m/Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold mb-0 dark:text-white text-sm">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                    <span class="text-xs px-2 py-1 rounded {{ $item->status === 'done' ? 'bg-success-100 text-success-600' : 'bg-warning-100 text-warning-600' }}">
                        {{ strtoupper($item->status) }}
                    </span>
                </div>
            </div>
            @empty
            <p class="text-center text-secondary-light py-4">Belum ada transaksi penjualan</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
