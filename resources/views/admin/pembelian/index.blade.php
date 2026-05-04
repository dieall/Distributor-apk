@extends('layouts.app')
@section('title', 'Purchase Order')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Purchase Order (PO)</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Kelola pembelian barang ke supplier</p>
    </div>
    <ul class="flex items-center gap-[6px]">
        <li class="font-medium"><a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard</a></li>
        <li>-</li>
        <li class="font-medium text-neutral-500">Purchase Order</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg mb-6">
    <div class="card-body p-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-sm font-medium mb-1 block dark:text-white">Cari PO</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control ps-10 w-full" placeholder="No. PO...">
                    <iconify-icon icon="ion:search-outline"
                        class="absolute start-3 top-1/2 -translate-y-1/2 text-secondary-light"></iconify-icon>
                </div>
            </div>
            <div class="min-w-[180px]">
                <label class="text-sm font-medium mb-1 block dark:text-white">Status</label>
                <select name="status" class="form-select w-full">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                    <option value="dikirim" {{ request('status')=='dikirim'?'selected':'' }}>Dikirim</option>
                    <option value="diterima" {{ request('status')=='diterima'?'selected':'' }}>Diterima</option>
                    <option value="dibatalkan" {{ request('status')=='dibatalkan'?'selected':'' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="btn btn-primary btn-sm px-4 py-2">Filter</button>
                @if(!auth()->user()->isDirektur())
                <a href="{{ fin_route('pembelian.create') }}" class="btn btn-success btn-sm px-4 py-2 flex items-center gap-2">
                    <iconify-icon icon="ri:add-line"></iconify-icon> Buat PO
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-lg">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr class="bg-neutral-50 dark:bg-neutral-600">
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">No. PO</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Supplier</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Tanggal</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Estimasi Tiba</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Total</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold text-neutral-600 dark:text-white text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelian as $po)
                    <tr>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-primary-600 text-sm">{{ $po->no_po }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-sm mb-0 dark:text-white">{{ $po->supplier->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $po->tanggal->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $po->tanggal_kirim_estimasi?->format('d M Y') ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-semibold dark:text-white">Rp {{ number_format($po->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="badge bg-{{ $po->status_color }}-focus text-{{ $po->status_color }}-600 radius-4 px-2 py-1 text-xs font-medium">
                                {{ $po->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-end">
                            <a href="{{ fin_route('pembelian.show', $po) }}" class="btn btn-outline-primary btn-sm px-3 py-2 flex items-center gap-1 radius-6 inline-flex">
                                <iconify-icon icon="ri:eye-line"></iconify-icon> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-secondary-light">
                            <iconify-icon icon="ri:file-list-3-line" class="text-4xl mb-2 block mx-auto"></iconify-icon>
                            <p class="mb-0">Belum ada Purchase Order</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pembelian->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200">{{ $pembelian->links() }}</div>
        @endif
    </div>
</div>
@endsection
