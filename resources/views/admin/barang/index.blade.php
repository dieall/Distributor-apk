@extends('layouts.app')
@section('title', 'Data Barang')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Data Barang</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Kelola master data barang/produk</p>
    </div>
    <ul class="flex items-center gap-[6px]">
        <li class="font-medium">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 hover:text-primary-600">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="font-medium text-neutral-500">Data Barang</li>
    </ul>
</div>

@include('partials.alert')

{{-- Filter & Actions --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg mb-6">
    <div class="card-body p-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-sm font-medium mb-1 block dark:text-white">Cari Barang</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control ps-10 w-full" placeholder="Kode, nama, atau kategori...">
                    <iconify-icon icon="ion:search-outline"
                        class="absolute start-3 top-1/2 -translate-y-1/2 text-secondary-light"></iconify-icon>
                </div>
            </div>
            <div class="min-w-[180px]">
                <label class="text-sm font-medium mb-1 block dark:text-white">Kategori</label>
                <select name="kategori" class="form-select w-full">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex items-center gap-2 px-4 py-2">
                    <iconify-icon icon="ri:search-line"></iconify-icon> Filter
                </button>
                @if(request()->hasAny(['search','kategori']))
                <a href="{{ route('admin.barang.index') }}" class="btn btn-outline-secondary btn-sm px-4 py-2">Reset</a>
                @endif
                <a href="{{ route('admin.barang.create') }}" class="btn btn-success btn-sm flex items-center gap-2 px-4 py-2 ms-auto">
                    <iconify-icon icon="ri:add-line"></iconify-icon> Tambah Barang
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr class="bg-neutral-50 dark:bg-neutral-600">
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">#</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Kode</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Nama Barang</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Kategori</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Satuan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Harga Jual</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Stok</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $item)
                    <tr>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $barang->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4">
                            <span class="badge bg-primary-100 text-primary-600 radius-4 px-2 py-1 text-xs font-medium">{{ $item->kode }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-neutral-800 dark:text-white mb-0">{{ $item->nama }}</p>
                            @if($item->deskripsi)
                            <p class="text-secondary-light text-xs mb-0 mt-1">{{ Str::limit($item->deskripsi, 40) }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $item->kategori }}</td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $item->satuan }}</td>
                        <td class="px-6 py-4 text-sm font-medium dark:text-white">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php $stokJumlah = $item->stok?->jumlah ?? 0; @endphp
                            <span class="font-semibold {{ $stokJumlah <= $item->stok_minimum ? 'text-danger-600' : 'text-success-600' }}">
                                {{ number_format($stokJumlah, 0, ',', '.') }}
                            </span>
                            <span class="text-secondary-light text-xs"> {{ $item->satuan }}</span>
                            @if($stokJumlah <= $item->stok_minimum)
                            <span class="badge bg-danger-focus text-danger-600 radius-4 px-2 py-1 text-xs block mt-1">Stok Rendah</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item->is_active)
                            <span class="badge bg-success-focus text-success-600 radius-4 px-2 py-1 text-xs font-medium">Aktif</span>
                            @else
                            <span class="badge bg-neutral-100 text-neutral-600 radius-4 px-2 py-1 text-xs font-medium">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-end">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.barang.show', $item) }}"
                                    class="btn btn-outline-primary btn-sm px-3 py-2 flex items-center gap-1 radius-6" title="Detail">
                                    <iconify-icon icon="ri:eye-line"></iconify-icon>
                                </a>
                                <a href="{{ route('admin.barang.edit', $item) }}"
                                    class="btn btn-outline-warning btn-sm px-3 py-2 flex items-center gap-1 radius-6" title="Edit">
                                    <iconify-icon icon="ri:edit-line"></iconify-icon>
                                </a>
                                <form action="{{ route('admin.barang.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Hapus barang {{ $item->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="btn btn-outline-danger btn-sm px-3 py-2 flex items-center radius-6" title="Hapus">
                                        <iconify-icon icon="ri:delete-bin-line"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-10 text-secondary-light">
                            <iconify-icon icon="ri:inbox-line" class="text-4xl mb-2 block mx-auto"></iconify-icon>
                            <p class="mb-0">Belum ada data barang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($barang->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200 flex items-center justify-between flex-wrap gap-3">
            <p class="text-sm text-secondary-light mb-0">
                Menampilkan {{ $barang->firstItem() }}-{{ $barang->lastItem() }} dari {{ $barang->total() }} data
            </p>
            {{ $barang->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
