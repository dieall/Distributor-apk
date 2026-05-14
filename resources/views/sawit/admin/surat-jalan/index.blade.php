@extends('layouts.sawit')
@section('title', 'Surat Jalan Sawit')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Surat Jalan Sawit</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Kelola surat jalan pengiriman sawit</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('sawit.admin.dashboard') }}" class="hover:text-primary-600">Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Surat Jalan</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex flex-wrap items-center justify-between gap-3">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Daftar Surat Jalan ({{ $suratJalan->total() }})</h6>
        <a href="{{ route('sawit.admin.surat-jalan.create') }}" class="px-4 py-2 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition flex items-center gap-2"><iconify-icon icon="ri:add-line"></iconify-icon> Buat Surat Jalan</a>
    </div>

    <div class="p-6">
        <form method="GET" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no surat jalan / perusahaan..." class="px-3 py-2 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-success-500 focus:outline-none">
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="px-3 py-2 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-success-500 focus:outline-none">
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="px-3 py-2 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-success-500 focus:outline-none">
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition">Cari</button>
                    <a href="{{ route('sawit.admin.surat-jalan.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition">Reset</a>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-200 dark:border-neutral-600">
                        <th class="text-left py-3 px-3 text-xs font-semibold text-neutral-600">No Surat Jalan</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-neutral-600">No Invoice</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-neutral-600">Tanggal</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-neutral-600">Perusahaan</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-neutral-600">Jenis Sawit</th>
                        <th class="text-right py-3 px-3 text-xs font-semibold text-neutral-600">Total Kg</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-neutral-600">No Mobil</th>
                        <th class="text-center py-3 px-3 text-xs font-semibold text-neutral-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suratJalan as $item)
                    <tr class="border-b border-neutral-100 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-600/50">
                        <td class="py-3 px-3 text-sm font-medium dark:text-white">{{ $item->no_surat_jalan }}</td>
                        <td class="py-3 px-3 text-sm text-secondary-light">{{ $item->penjualan->no_invoice }}</td>
                        <td class="py-3 px-3 text-sm text-secondary-light">{{ $item->tanggal->format('d/m/Y') }}</td>
                        <td class="py-3 px-3 text-sm dark:text-white">{{ $item->nama_perusahaan }}</td>
                        <td class="py-3 px-3 text-sm text-secondary-light">{{ $item->jenis_sawit }}</td>
                        <td class="py-3 px-3 text-sm text-right dark:text-white">{{ number_format($item->total_kg, 2) }}</td>
                        <td class="py-3 px-3 text-sm text-secondary-light">{{ $item->no_mobil }}</td>
                        <td class="py-3 px-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('sawit.admin.surat-jalan.show', $item) }}" class="w-7 h-7 rounded bg-info-100 text-info-600 hover:bg-info-200 flex items-center justify-center"><iconify-icon icon="ri:eye-line" class="text-sm"></iconify-icon></a>
                                <a href="{{ route('sawit.admin.surat-jalan.print', $item) }}" target="_blank" class="w-7 h-7 rounded bg-primary-100 text-primary-600 hover:bg-primary-200 flex items-center justify-center"><iconify-icon icon="ri:printer-line" class="text-sm"></iconify-icon></a>
                                <form action="{{ route('sawit.admin.surat-jalan.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')
                                    <button class="w-7 h-7 rounded bg-danger-100 text-danger-600 hover:bg-danger-200 flex items-center justify-center"><iconify-icon icon="ri:delete-bin-line" class="text-sm"></iconify-icon></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="py-8 text-center text-secondary-light">Belum ada surat jalan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($suratJalan->hasPages())<div class="mt-6">{{ $suratJalan->links() }}</div>@endif
    </div>
</div>
@endsection
