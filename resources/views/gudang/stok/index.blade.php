@extends('layouts.app')
@section('title', 'Stok Gudang')

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h6 class="font-semibold mb-0">Stok Gudang</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Dashboard data stok & harga rata-rata barang</p>
    </div>
    <ul class="flex items-center gap-2">
        <li class="font-medium"><a href="{{ route('gudang.dashboard') }}" class="flex items-center gap-1 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard</a></li>
        <li>-</li>
        <li class="font-medium text-neutral-500">Stok Gudang</li>
    </ul>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <div class="col-xxl-4 col-sm-4">
        <div class="card shadow-none border border-neutral-200 rounded-lg h-100">
            <div class="card-body p-5 flex items-center gap-4">
                <div class="w-48-px h-48-px bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:box-3-line" class="text-primary-600 text-2xl"></iconify-icon>
                </div>
                <div>
                    <p class="text-secondary-light text-sm font-medium mb-1">Total Jenis Barang</p>
                    <h5 class="font-bold mb-0">{{ number_format($totalItem, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-4 col-sm-4">
        <div class="card shadow-none border border-neutral-200 rounded-lg h-100">
            <div class="card-body p-5 flex items-center gap-4">
                <div class="w-48-px h-48-px bg-success-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:money-dollar-circle-line" class="text-success-600 text-2xl"></iconify-icon>
                </div>
                <div>
                    <p class="text-secondary-light text-sm font-medium mb-1">Total Nilai Stok</p>
                    <h5 class="font-bold mb-0 text-success-600">Rp {{ number_format($totalNilai, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-4 col-sm-4">
        <div class="card shadow-none border border-neutral-200 rounded-lg h-100">
            <div class="card-body p-5 flex items-center gap-4">
                <div class="w-48-px h-48-px bg-{{ $stokRendah > 0 ? 'danger' : 'success' }}-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <iconify-icon icon="ri:alert-line" class="text-{{ $stokRendah > 0 ? 'danger' : 'success' }}-600 text-2xl"></iconify-icon>
                </div>
                <div>
                    <p class="text-secondary-light text-sm font-medium mb-1">Stok Rendah</p>
                    <h5 class="font-bold mb-0 text-{{ $stokRendah > 0 ? 'danger' : 'success' }}-600">{{ $stokRendah }} barang</h5>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Search --}}
<div class="card shadow-none border border-neutral-200 rounded-lg mb-6">
    <div class="card-body p-5">
        <form method="GET" class="flex gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control ps-10" placeholder="Cari nama atau kode barang...">
                <iconify-icon icon="ion:search-outline" class="absolute start-3 top-50 translate-middle-y text-secondary-light"></iconify-icon>
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-4 py-2.5">Cari</button>
            @if(request('search'))
            <a href="{{ route('gudang.stok.index') }}" class="btn btn-outline-secondary btn-sm px-4 py-2.5">Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card shadow-none border border-neutral-200 rounded-lg">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold">Kode</th>
                        <th class="px-6 py-4 text-sm font-semibold">Nama Barang</th>
                        <th class="px-6 py-4 text-sm font-semibold">Kategori</th>
                        <th class="px-6 py-4 text-sm font-semibold text-right">Stok</th>
                        <th class="px-6 py-4 text-sm font-semibold">Min. Stok</th>
                        <th class="px-6 py-4 text-sm font-semibold text-right">Harga Rata-rata</th>
                        <th class="px-6 py-4 text-sm font-semibold text-right">Nilai Stok</th>
                        <th class="px-6 py-4 text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stok as $item)
                    @php
                        $jumlah = $item->stok?->jumlah ?? 0;
                        $rendah = $jumlah <= $item->stok_minimum;
                    @endphp
                    <tr class="{{ $rendah ? 'bg-danger-50' : '' }}">
                        <td class="px-6 py-4">
                            <span class="badge bg-neutral-100 text-neutral-700 radius-4 px-8 py-4 text-xs">{{ $item->kode }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-sm mb-0">{{ $item->nama }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $item->kategori }}</td>
                        <td class="px-6 py-4 text-right">
                            <span class="font-bold {{ $rendah ? 'text-danger-600' : 'text-success-600' }}">
                                {{ format_qty_id($jumlah) }}
                            </span>
                            <span class="text-xs text-secondary-light"> {{ $item->satuan }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $item->stok_minimum }} {{ $item->satuan }}</td>
                        <td class="px-6 py-4 text-right text-sm">Rp {{ number_format($item->stok?->harga_rata ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-semibold">
                            Rp {{ number_format($jumlah * ($item->stok?->harga_rata ?? 0), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($rendah)
                            <span class="badge bg-danger-focus text-danger-600 radius-4 px-8 py-4 text-xs font-medium">
                                <iconify-icon icon="ri:alert-line"></iconify-icon> Rendah
                            </span>
                            @else
                            <span class="badge bg-success-focus text-success-600 radius-4 px-8 py-4 text-xs font-medium">Aman</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('gudang.stok.show', $item) }}" class="btn btn-outline-primary btn-sm px-10 py-6 flex items-center gap-1 inline-flex">
                                <iconify-icon icon="ri:history-line"></iconify-icon> Mutasi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-10 text-secondary-light">
                            <iconify-icon icon="ri:inbox-line" class="text-4xl mb-2 block mx-auto"></iconify-icon>
                            Belum ada data stok
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stok->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200">{{ $stok->links() }}</div>
        @endif
    </div>
</div>
@endsection
