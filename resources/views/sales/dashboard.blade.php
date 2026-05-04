@extends('layouts.app')
@section('title', 'Dashboard Sales')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <h6 class="font-semibold mb-0 dark:text-white">Dashboard Sales</h6>
    <ul class="flex items-center gap-[6px]">
        <li class="font-medium"><a href="{{ route('sales.dashboard') }}" class="flex items-center gap-2 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard</a></li>
        <li>-</li>
        <li class="font-medium">Sales</li>
    </ul>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Total Order</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-success-100 text-success-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:shopping-cart-2-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Order bulan ini</p>
        </div>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Total Penjualan</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">Rp 0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-primary-100 text-primary-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:money-dollar-circle-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Pendapatan bulan ini</p>
        </div>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Total Pelanggan</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-warning-100 text-warning-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:user-heart-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Pelanggan aktif</p>
        </div>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
        <div class="card-body p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-medium text-secondary-light mb-1 text-sm">Target Bulan</p>
                    <h5 class="font-semibold mb-0 dark:text-white text-2xl">0%</h5>
                </div>
                <div class="w-[48px] h-[48px] bg-danger-100 text-danger-600 rounded-full flex justify-center items-center flex-shrink-0">
                    <iconify-icon icon="ri:bar-chart-line" class="text-2xl"></iconify-icon>
                </div>
            </div>
            <p class="font-medium text-sm text-secondary-light mt-3 mb-0">Pencapaian target</p>
        </div>
    </div>
</div>

{{-- Ringkasan stok gudang (read-only untuk sales) --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg mb-6 overflow-hidden">
    <div class="card-body p-0">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <iconify-icon icon="ri:store-2-line" class="text-primary-600 text-xl"></iconify-icon>
                <div>
                    <h6 class="font-semibold mb-0 dark:text-white text-sm">Sisa stok barang (semua)</h6>
                    <p class="text-secondary-light text-xs mb-0">Barang aktif — akumulasi kuantitas &amp; nilai persediaan (harga rata-rata)</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-neutral-200 dark:divide-neutral-600">
            <div class="p-5 text-center sm:text-left">
                <p class="text-xs text-secondary-light font-medium mb-1">Total kuantitas stok</p>
                <p class="text-2xl font-bold text-primary-600 mb-0">{{ format_qty_id($totalStokQty) }}</p>
                <p class="text-[11px] text-secondary-light mt-1 mb-0">Penjumlahan semua satuan per barang</p>
            </div>
            <div class="p-5 text-center sm:text-left">
                <p class="text-xs text-secondary-light font-medium mb-1">Nilai persediaan</p>
                <p class="text-2xl font-bold text-success-600 mb-0">Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</p>
                <p class="text-[11px] text-secondary-light mt-1 mb-0">Σ (stok × harga rata)</p>
            </div>
            <div class="p-5 text-center sm:text-left">
                <p class="text-xs text-secondary-light font-medium mb-1">Jenis barang aktif</p>
                <p class="text-2xl font-bold dark:text-white mb-0">{{ number_format($jumlahSkuAktif, 0, ',', '.') }}</p>
                <p class="text-[11px] text-secondary-light mt-1 mb-0">SKU di master data</p>
            </div>
        </div>
        <div class="overflow-x-auto max-h-[420px] overflow-y-auto">
            <table class="w-full text-sm mb-0">
                <thead class="bg-neutral-50 dark:bg-neutral-800 sticky top-0 z-10">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Kode</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Nama</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Sisa stok</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Harga jual</th>
                        
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                    @forelse($barangStok as $b)
                    @php
                        $jml = (float) ($b->stok?->jumlah ?? 0);
                        $hrata = (float) ($b->stok?->harga_rata ?? 0);
                    @endphp
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600/30">
                        <td class="px-5 py-2.5 font-mono text-xs text-secondary-light">{{ $b->kode }}</td>
                        <td class="px-5 py-2.5 font-medium dark:text-white">{{ $b->nama }}</td>
                        <td class="px-5 py-2.5 text-right font-semibold whitespace-nowrap {{ $jml <= (float) $b->stok_minimum ? 'text-danger-500' : 'text-success-600' }}">
                            {{ format_qty_id($jml) }} <span class="text-xs font-normal text-secondary-light">{{ $b->satuan }}</span>
                        </td>
                        <td class="px-5 py-2.5 text-right text-xs whitespace-nowrap">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                    
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-secondary-light">Tidak ada barang aktif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
