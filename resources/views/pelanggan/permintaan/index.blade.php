@extends('layouts.app')
@section('title', 'Permintaan Saya')

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h6 class="font-semibold mb-0">Permintaan Barang Saya</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Daftar permintaan barang yang telah Anda ajukan</p>
    </div>
    <div class="flex items-center gap-3">
        <ul class="flex items-center gap-2">
            <li class="font-medium"><a href="{{ route('pelanggan.dashboard') }}" class="hover:text-primary-600">Dashboard</a></li>
            <li>-</li>
            <li class="font-medium text-neutral-500">Permintaan</li>
        </ul>
        <a href="{{ route('pelanggan.permintaan.create') }}" class="btn btn-primary btn-sm px-4 py-8 flex items-center gap-2">
            <iconify-icon icon="ri:add-line"></iconify-icon> Buat Permintaan
        </a>
    </div>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 rounded-lg">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-4 text-sm font-semibold">No. Permintaan</th>
                        <th class="px-6 py-4 text-sm font-semibold">Tgl. Request</th>
                        <th class="px-6 py-4 text-sm font-semibold">Dibutuhkan</th>
                        <th class="px-6 py-4 text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold">Surat Jalan</th>
                        <th class="px-6 py-4 text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaan as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-primary-600 text-sm">{{ $item->no_permintaan }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $item->tanggal_request->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-secondary-light">{{ $item->tanggal_dibutuhkan?->format('d M Y') ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="badge bg-{{ $item->status_color }}-focus text-{{ $item->status_color }}-600 radius-4 px-8 py-4 text-xs font-medium">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($item->suratJalan)
                            <span class="font-medium text-success-600">{{ $item->suratJalan->no_sj }}</span>
                            @else
                            <span class="text-secondary-light">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('pelanggan.permintaan.show', $item) }}" class="btn btn-outline-primary btn-sm px-10 py-6 flex items-center gap-1 inline-flex">
                                <iconify-icon icon="ri:eye-line"></iconify-icon> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-secondary-light">
                            <iconify-icon icon="ri:shopping-cart-line" class="text-4xl mb-2 block mx-auto"></iconify-icon>
                            <p class="mb-0">Belum ada permintaan. <a href="{{ route('pelanggan.permintaan.create') }}" class="text-primary-600">Buat sekarang</a></p>
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
