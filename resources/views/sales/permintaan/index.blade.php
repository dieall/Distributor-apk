@extends('layouts.app')
@section('title', 'Permintaan Barang')

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h6 class="font-semibold mb-0">Permintaan Barang</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Kelola permintaan barang dari pelanggan</p>
    </div>
    <div class="flex items-center gap-3">
        <ul class="flex items-center gap-2">
            <li class="font-medium"><a href="{{ route('sales.dashboard') }}" class="flex items-center gap-1 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard</a></li>
            <li>-</li>
            <li class="font-medium text-neutral-500">Permintaan Barang</li>
        </ul>
        <a href="{{ route('sales.permintaan.create') }}" class="btn btn-primary btn-sm px-4 py-2 flex items-center gap-2">
            <iconify-icon icon="ri:add-line"></iconify-icon> Buat Permintaan
        </a>
    </div>
</div>

@include('partials.alert')

{{-- Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    <div class="col-sm-4">
        <div class="card shadow-none border border-warning-200 bg-warning-50 rounded-lg">
            <div class="card-body p-16 flex items-center gap-3">
                <div class="w-40-px h-40-px bg-warning-100 rounded-full flex items-center justify-center">
                    <iconify-icon icon="ri:time-line" class="text-warning-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="text-warning-700 text-xs font-medium mb-0">Pending</p>
                    <h5 class="font-bold mb-0 text-warning-700">{{ $counts['pending'] }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card shadow-none border border-info-200 bg-info-50 rounded-lg">
            <div class="card-body p-16 flex items-center gap-3">
                <div class="w-40-px h-40-px bg-info-100 rounded-full flex items-center justify-center">
                    <iconify-icon icon="ri:loader-4-line" class="text-info-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="text-info-700 text-xs font-medium mb-0">Diproses</p>
                    <h5 class="font-bold mb-0 text-info-700">{{ $counts['diproses'] }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card shadow-none border border-primary-200 bg-primary-50 rounded-lg">
            <div class="card-body p-16 flex items-center gap-3">
                <div class="w-40-px h-40-px bg-primary-100 rounded-full flex items-center justify-center">
                    <iconify-icon icon="ri:truck-line" class="text-primary-600 text-xl"></iconify-icon>
                </div>
                <div>
                    <p class="text-primary-700 text-xs font-medium mb-0">Siap Kirim</p>
                    <h5 class="font-bold mb-0 text-primary-700">{{ $counts['siap_kirim'] }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card shadow-none border border-neutral-200 rounded-lg mb-6">
    <div class="card-body p-5">
        <form method="GET" class="flex flex-wrap gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control ps-10" placeholder="No. permintaan atau nama pelanggan...">
                <iconify-icon icon="ion:search-outline" class="absolute start-3 top-50 translate-middle-y text-secondary-light"></iconify-icon>
            </div>
            <div style="min-width: 180px;">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="diproses" {{ request('status')=='diproses'?'selected':'' }}>Diproses</option>
                    <option value="siap_kirim" {{ request('status')=='siap_kirim'?'selected':'' }}>Siap Kirim</option>
                    <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-4 py-2.5">Filter</button>
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
                        <th class="px-6 py-4 text-sm font-semibold">No. Permintaan</th>
                        <th class="px-6 py-4 text-sm font-semibold">Pelanggan</th>
                        <th class="px-6 py-4 text-sm font-semibold">Tgl. Request</th>
                        <th class="px-6 py-4 text-sm font-semibold">Dibutuhkan</th>
                        <th class="px-6 py-4 text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaan as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-primary-600 text-sm">{{ $item->no_permintaan }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-sm mb-0">{{ $item->pelanggan->name }}</p>
                            <p class="text-secondary-light text-xs mb-0">{{ $item->pelanggan->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $item->tanggal_request->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $item->tanggal_dibutuhkan?->format('d M Y') ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="badge bg-{{ $item->status_color }}-focus text-{{ $item->status_color }}-600 radius-4 px-8 py-4 text-xs font-medium">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('sales.permintaan.show', $item) }}" class="btn btn-outline-primary btn-sm px-10 py-6 flex items-center gap-1 inline-flex">
                                    <iconify-icon icon="ri:eye-line"></iconify-icon>
                                    @if($item->status === 'pending') Proses @else Detail @endif
                                </a>
                                @if($item->status === 'pending')
                                <a href="{{ route('sales.permintaan.edit', $item) }}" class="btn btn-outline-warning btn-sm px-10 py-6 flex items-center gap-1 inline-flex">
                                    <iconify-icon icon="ri:edit-line"></iconify-icon>
                                </a>
                                <form action="{{ route('sales.permintaan.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus permintaan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm px-10 py-6 flex items-center gap-1 inline-flex">
                                        <iconify-icon icon="ri:delete-bin-line"></iconify-icon>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-secondary-light">
                            <iconify-icon icon="ri:shopping-cart-line" class="text-4xl mb-2 block mx-auto"></iconify-icon>
                            Belum ada permintaan barang
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($permintaan->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200">{{ $permintaan->links() }}</div>
        @endif
    </div>
</div>
@endsection
