@extends('layouts.app')
@section('title', 'Pengeluaran Barang')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Pengeluaran Barang</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Catat barang keluar gudang selain penjualan (rusak, kadaluarsa, sampel, dll.)</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('gudang.dashboard') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100">Dashboard</a>
        <a href="{{ route('gudang.pengeluaran-barang.create') }}" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium flex items-center gap-2">
            <iconify-icon icon="ri:add-line"></iconify-icon> Catat Pengeluaran
        </a>
    </div>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl mb-5 p-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-secondary-light mb-1">Cari nomor dokumen</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="PB-..."
                class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
        </div>
        <button type="submit" class="px-4 py-2.5 rounded-lg bg-neutral-900 dark:bg-primary-600 text-white text-sm font-medium">Filter</button>
        <a href="{{ route('gudang.pengeluaran-barang.index') }}" class="px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm text-neutral-600 dark:text-neutral-200 hover:bg-neutral-100">Reset</a>
    </form>
</div>

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-600">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">No. Dokumen</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Tanggal</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Alasan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Oleh</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @forelse($items as $item)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600/40 transition">
                    <td class="px-5 py-3.5 font-bold text-danger-600">{{ $item->no_pengeluaran }}</td>
                    <td class="px-5 py-3.5 text-sm dark:text-white">{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 text-sm dark:text-white">{{ $item->alasan_label }}</td>
                    <td class="px-5 py-3.5 text-sm text-secondary-light">{{ $item->dibuatOleh->name ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <a href="{{ route('gudang.pengeluaran-barang.show', $item) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 text-primary-600 hover:bg-primary-200" title="Detail">
                            <iconify-icon icon="ri:eye-line"></iconify-icon>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-secondary-light">Belum ada pengeluaran barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4">{{ $items->links() }}</div>
</div>
@endsection
