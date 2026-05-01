@extends('layouts.app')
@section('title', 'Penerimaan Barang')

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h6 class="font-semibold mb-0">Penerimaan Barang</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Catat penerimaan barang dari supplier</p>
    </div>
    <ul class="flex items-center gap-2">
        <li class="font-medium"><a href="{{ route('gudang.dashboard') }}" class="flex items-center gap-1 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard</a></li>
        <li>-</li>
        <li class="font-medium text-neutral-500">Penerimaan Barang</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 rounded-lg mb-6">
    <div class="card-body p-5 flex gap-3 flex-wrap items-center">
        <form method="GET" class="flex-1 flex gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control ps-10" placeholder="No. penerimaan...">
                <iconify-icon icon="ion:search-outline" class="absolute start-3 top-50 translate-middle-y text-secondary-light"></iconify-icon>
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-4 py-2.5">Filter</button>
        </form>
        <a href="{{ route('gudang.penerimaan.create') }}" class="btn btn-success btn-sm px-4 py-2.5 flex items-center gap-2">
            <iconify-icon icon="ri:add-line"></iconify-icon> Terima Barang
        </a>
    </div>
</div>

<div class="card shadow-none border border-neutral-200 rounded-lg">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold">No. Penerimaan</th>
                        <th class="px-6 py-4 text-sm font-semibold">No. PO</th>
                        <th class="px-6 py-4 text-sm font-semibold">Supplier</th>
                        <th class="px-6 py-4 text-sm font-semibold">Tanggal</th>
                        <th class="px-6 py-4 text-sm font-semibold">Diterima Oleh</th>
                        <th class="px-6 py-4 text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penerimaan as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-success-600 text-sm">{{ $item->no_penerimaan }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.pembelian.show', $item->pembelian) }}" class="text-primary-600 text-sm hover:text-primary-600 font-medium">{{ $item->pembelian->no_po }}</a>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $item->pembelian->supplier->name }}</td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $item->tanggal->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm">{{ $item->diterima->name }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('gudang.penerimaan.show', $item) }}" class="btn btn-outline-primary btn-sm px-10 py-6 flex items-center gap-1 inline-flex">
                                <iconify-icon icon="ri:eye-line"></iconify-icon> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-secondary-light">
                            <iconify-icon icon="ri:inbox-line" class="text-4xl mb-2 block mx-auto"></iconify-icon>
                            <p class="mb-0">Belum ada data penerimaan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($penerimaan->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200">{{ $penerimaan->links() }}</div>
        @endif
    </div>
</div>
@endsection
