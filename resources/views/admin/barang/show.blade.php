@extends('layouts.app')
@section('title', 'Detail Barang')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Detail Barang</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">{{ $barang->kode }} — {{ $barang->nama }}</p>
    </div>
    <div class="flex items-center gap-3">
        <ul class="flex items-center gap-[6px] text-sm">
            <li class="font-medium"><a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="text-base"></iconify-icon>Dashboard</a></li>
            <li class="text-neutral-400">/</li>
            <li class="font-medium"><a href="{{ route('admin.barang.index') }}" class="hover:text-primary-600">Data Barang</a></li>
            <li class="text-neutral-400">/</li>
            <li class="text-primary-600 font-medium">Detail</li>
        </ul>
        <a href="{{ route('admin.barang.edit', $barang) }}"
            class="px-4 py-2 rounded-lg bg-warning-500 hover:bg-warning-600 text-white text-sm font-medium flex items-center gap-1.5 transition">
            <iconify-icon icon="ri:edit-line"></iconify-icon> Edit
        </a>
    </div>
</div>

{{-- Stats Bar --}}
@php $stokJumlah = $barang->stok_jumlah ?? 0; @endphp
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 text-center">
        <p class="text-xs text-secondary-light font-medium mb-1">Stok Saat Ini</p>
        <p class="text-xl font-bold mb-0 {{ $barang->isStokRendah() ? 'text-red-500' : 'text-success-600' }}">{{ number_format($stokJumlah, 0, ',', '.') }}</p>
        <p class="text-xs text-secondary-light mb-0">{{ $barang->satuan }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 text-center">
        <p class="text-xs text-secondary-light font-medium mb-1">Harga Jual</p>
        <p class="text-base font-bold text-success-600 mb-0">Rp {{ number_format($barang->harga_jual, 0, ',', '.') }}</p>
        <p class="text-xs text-secondary-light mb-0">per {{ $barang->satuan }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 text-center">
        <p class="text-xs text-secondary-light font-medium mb-1">Harga MBG</p>
        <p class="text-base font-bold text-primary-600 mb-0">Rp {{ number_format($barang->harga_mbg ?? $barang->harga_jual, 0, ',', '.') }}</p>
        <p class="text-xs text-secondary-light mb-0">acuan pelanggan</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 text-center">
        <p class="text-xs text-secondary-light font-medium mb-1">Harga Rata-rata</p>
        <p class="text-base font-bold dark:text-white mb-0">Rp {{ number_format($barang->harga_rata ?? 0, 0, ',', '.') }}</p>
        <p class="text-xs text-secondary-light mb-0">rata-rata beli</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 text-center">
        <p class="text-xs text-secondary-light font-medium mb-1">Stok Minimum</p>
        <p class="text-base font-bold dark:text-white mb-0">{{ $barang->stok_minimum }}</p>
        <p class="text-xs text-secondary-light mb-0">{{ $barang->satuan }}</p>
    </div>
</div>

{{-- Main Info Card --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:information-line" class="text-primary-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Informasi Barang</h6>
        @if($barang->is_active)
        <span class="ml-auto px-2.5 py-1 rounded-full bg-success-100 text-success-600 text-xs font-semibold flex items-center gap-1">
            <span class="w-1.5 h-1.5 rounded-full bg-success-500 inline-block"></span> Aktif
        </span>
        @else
        <span class="ml-auto px-2.5 py-1 rounded-full bg-neutral-200 text-neutral-500 text-xs font-semibold">Non-Aktif</span>
        @endif
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-5">
            <div>
                <p class="text-xs text-secondary-light font-medium mb-1">Kode Barang</p>
                <p class="font-semibold text-sm dark:text-white">{{ $barang->kode }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary-light font-medium mb-1">Nama Barang</p>
                <p class="font-semibold text-sm dark:text-white">{{ $barang->nama }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary-light font-medium mb-1">Kategori</p>
                <p class="font-semibold text-sm dark:text-white">{{ $barang->kategori }}</p>
            </div>
            <div>
                <p class="text-xs text-secondary-light font-medium mb-1">Satuan</p>
                <p class="font-semibold text-sm dark:text-white">{{ $barang->satuan }}</p>
            </div>
            @if($barang->deskripsi)
            <div class="col-span-2 sm:col-span-3 lg:col-span-4">
                <p class="text-xs text-secondary-light font-medium mb-1">Deskripsi</p>
                <p class="text-sm text-neutral-700 dark:text-neutral-200 mb-0">{{ $barang->deskripsi }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Mutasi Stok Table --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <iconify-icon icon="ri:history-line" class="text-info-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Riwayat Mutasi Stok</h6>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs text-secondary-light">{{ $barang->stokMutasi->count() }} transaksi</span>
            @php $totalMasuk = $barang->stokMutasi->where('tipe','masuk')->sum('jumlah'); $totalKeluar = $barang->stokMutasi->where('tipe','keluar')->sum('jumlah'); @endphp
            <span class="text-xs font-medium text-success-600">+{{ number_format($totalMasuk,0,',','.') }} masuk</span>
            <span class="text-xs font-medium text-red-500">-{{ number_format($totalKeluar,0,',','.') }} keluar</span>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide">Tanggal</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide">Tipe</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide">Jumlah</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide">Harga/Satuan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide">Referensi</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide">Oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @forelse($barang->stokMutasi as $m)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                    <td class="px-5 py-3.5 text-xs text-secondary-light whitespace-nowrap">
                        {{ $m->created_at->format('d M Y') }}<br>
                        <span class="text-neutral-400">{{ $m->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        @if($m->tipe === 'masuk')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-success-100 text-success-600 text-xs font-semibold">
                            <iconify-icon icon="ri:arrow-up-line" class="text-xs"></iconify-icon> Masuk
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-100 text-red-500 text-xs font-semibold">
                            <iconify-icon icon="ri:arrow-down-line" class="text-xs"></iconify-icon> Keluar
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right font-bold dark:text-white whitespace-nowrap">
                        {{ number_format($m->jumlah, 0, ',', '.') }} <span class="text-xs font-normal text-secondary-light">{{ $barang->satuan }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-right text-xs text-secondary-light whitespace-nowrap">Rp {{ number_format($m->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5 text-xs {{ $m->referensi ? 'text-primary-600 font-semibold' : 'text-neutral-400' }}">{{ $m->referensi ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-xs text-secondary-light">{{ $m->user->name ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <iconify-icon icon="ri:inbox-line" class="text-3xl text-neutral-300 block mx-auto mb-2"></iconify-icon>
                        <p class="text-sm text-secondary-light mb-0">Belum ada riwayat mutasi stok</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
