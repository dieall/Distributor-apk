@extends('layouts.sawit')
@section('title', 'Detail Barang Sawit')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Detail Barang Sawit</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Informasi lengkap barang sawit</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('sawit.admin.dashboard') }}" class="flex items-center gap-1 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="text-base"></iconify-icon>Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="font-medium"><a href="{{ route('sawit.admin.barang.index') }}" class="hover:text-primary-600">Data Barang</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Detail</li>
    </ul>
</div>

@include('partials.alert')

{{-- Info Barang --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-info-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:box-3-line" class="text-info-600 text-lg"></iconify-icon>
            </div>
            <div>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Informasi Barang</h6>
                <p class="text-secondary-light text-xs mb-0">{{ $barang->id_sawit }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('sawit.admin.barang.edit', $barang) }}" class="px-4 py-2 rounded-lg bg-warning-600 hover:bg-warning-700 text-white text-sm font-medium transition flex items-center gap-2">
                <iconify-icon icon="ri:edit-line"></iconify-icon> Edit
            </a>
        </div>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">ID Sawit</label>
                <p class="text-sm font-semibold dark:text-white">{{ $barang->id_sawit }}</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Nama Sawit</label>
                <p class="text-sm font-semibold dark:text-white">{{ $barang->nama_sawit }}</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Total Stok</label>
                <p class="text-2xl font-bold {{ $barang->total_kg > 0 ? 'text-success-600' : 'text-danger-600' }}">
                    {{ number_format($barang->total_kg, 2) }} Kg
                </p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Status</label>
                <span class="inline-block text-xs px-3 py-1 rounded {{ $barang->is_active ? 'bg-success-100 text-success-600' : 'bg-danger-100 text-danger-600' }}">
                    {{ $barang->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
            @if($barang->keterangan)
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-neutral-500 dark:text-neutral-400 mb-1">Keterangan</label>
                <p class="text-sm dark:text-white">{{ $barang->keterangan }}</p>
            </div>
            @endif>
        </div>
    </div>
</div>

{{-- Statistik --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:inbox-archive-line" class="text-success-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Pembelian</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($totalPembelian, 2) }} Kg</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-warning-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:shopping-cart-line" class="text-warning-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Total Penjualan</p>
                <h6 class="font-semibold mb-0 dark:text-white">{{ number_format($totalPenjualan, 2) }} Kg</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-info-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:money-dollar-circle-line" class="text-info-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Nilai Pembelian</p>
                <h6 class="font-semibold mb-0 dark:text-white text-xs">Rp {{ number_format($nilaiPembelian, 0, ',', '.') }}</h6>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:wallet-3-line" class="text-primary-600 text-2xl"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs text-secondary-light mb-1">Nilai Penjualan</p>
                <h6 class="font-semibold mb-0 dark:text-white text-xs">Rp {{ number_format($nilaiPenjualan, 0, ',', '.') }}</h6>
            </div>
        </div>
    </div>
</div>

{{-- Riwayat Transaksi --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Pembelian --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Riwayat Pembelian (Done)</h6>
        </div>
        <div class="p-6">
            @forelse($barang->pembelian->take(5) as $item)
            <div class="flex items-center justify-between py-3 border-b border-neutral-100 dark:border-neutral-600 last:border-0">
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">{{ $item->no_pembelian }}</p>
                    <p class="text-xs text-secondary-light mb-0">{{ $item->tanggal->format('d/m/Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold mb-0 text-success-600 text-sm">+{{ number_format($item->qty_setelah_refaksi, 2) }} Kg</p>
                    <p class="text-xs text-secondary-light mb-0">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-secondary-light py-4 text-sm">Belum ada transaksi pembelian</p>
            @endforelse
        </div>
    </div>

    {{-- Penjualan --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Riwayat Penjualan (Done)</h6>
        </div>
        <div class="p-6">
            @forelse($barang->penjualan->take(5) as $item)
            <div class="flex items-center justify-between py-3 border-b border-neutral-100 dark:border-neutral-600 last:border-0">
                <div>
                    <p class="font-semibold mb-0 dark:text-white text-sm">{{ $item->no_invoice }}</p>
                    <p class="text-xs text-secondary-light mb-0">{{ $item->tanggal->format('d/m/Y') }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold mb-0 text-danger-600 text-sm">-{{ number_format($item->total_kg_setelah_refaksi, 2) }} Kg</p>
                    <p class="text-xs text-secondary-light mb-0">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-secondary-light py-4 text-sm">Belum ada transaksi penjualan</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
