@extends('layouts.sawit')
@section('title', 'Data Barang Sawit')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Data Barang Sawit</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Kelola data barang sawit</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('sawit.admin.dashboard') }}" class="flex items-center gap-1 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="text-base"></iconify-icon>Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Data Barang</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-success-100 flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:box-3-line" class="text-success-600 text-lg"></iconify-icon>
            </div>
            <div>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Daftar Barang Sawit</h6>
                <p class="text-secondary-light text-xs mb-0">Total: {{ $barang->total() }} barang</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('sawit.admin.barang.export', request()->query()) }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-600 transition flex items-center gap-2">
                <iconify-icon icon="ri:file-excel-line"></iconify-icon> Export Excel
            </a>
            <a href="{{ route('sawit.admin.barang.create') }}" class="px-4 py-2 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition flex items-center gap-2 shadow-sm">
                <iconify-icon icon="ri:add-line"></iconify-icon> Tambah Barang
            </a>
        </div>
    </div>

    <div class="p-6">
        {{-- Filter & Search --}}
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID atau Nama Sawit..." class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <select name="status" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition">
                        <iconify-icon icon="ri:search-line" class="text-base"></iconify-icon> Cari
                    </button>
                    <a href="{{ route('sawit.admin.barang.index') }}" class="px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-600 transition">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-200 dark:border-neutral-600">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-neutral-600 dark:text-neutral-300">ID Sawit</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-neutral-600 dark:text-neutral-300">Nama Sawit</th>
                        <th class="text-right py-3 px-4 text-sm font-semibold text-neutral-600 dark:text-neutral-300">Total Kg</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-neutral-600 dark:text-neutral-300">Status</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-neutral-600 dark:text-neutral-300">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $item)
                    <tr class="border-b border-neutral-100 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-600/50">
                        <td class="py-3 px-4 text-sm font-medium dark:text-white">{{ $item->id_sawit }}</td>
                        <td class="py-3 px-4 text-sm dark:text-white">{{ $item->nama_sawit }}</td>
                        <td class="py-3 px-4 text-sm text-right dark:text-white">
                            <span class="font-semibold {{ $item->total_kg > 0 ? 'text-success-600' : 'text-danger-600' }}">
                                {{ number_format($item->total_kg, 2) }} Kg
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="text-xs px-2 py-1 rounded {{ $item->is_active ? 'bg-success-100 text-success-600' : 'bg-danger-100 text-danger-600' }}">
                                {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('sawit.admin.barang.show', $item) }}" class="w-8 h-8 rounded-lg bg-info-100 text-info-600 hover:bg-info-200 flex items-center justify-center transition" title="Detail">
                                    <iconify-icon icon="ri:eye-line" class="text-base"></iconify-icon>
                                </a>
                                <a href="{{ route('sawit.admin.barang.edit', $item) }}" class="w-8 h-8 rounded-lg bg-warning-100 text-warning-600 hover:bg-warning-200 flex items-center justify-center transition" title="Edit">
                                    <iconify-icon icon="ri:edit-line" class="text-base"></iconify-icon>
                                </a>
                                <form action="{{ route('sawit.admin.barang.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-danger-100 text-danger-600 hover:bg-danger-200 flex items-center justify-center transition" title="Hapus">
                                        <iconify-icon icon="ri:delete-bin-line" class="text-base"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-secondary-light">
                            <iconify-icon icon="ri:inbox-line" class="text-4xl mb-2"></iconify-icon>
                            <p class="text-sm">Belum ada data barang sawit</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($barang->hasPages())
        <div class="mt-6">
            {{ $barang->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
