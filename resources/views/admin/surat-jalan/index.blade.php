@extends('layouts.app')
@section('title', 'Daftar Surat Jalan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Surat Jalan</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Kelola surat pengiriman barang ke pelanggan</p>
    </div>
    <a href="{{ route('admin.surat-jalan.create') }}" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium flex items-center gap-2">
        <iconify-icon icon="ri:add-line"></iconify-icon> Buat Surat Jalan
    </a>
</div>

@include('partials.alert')

{{-- Filter --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl mb-5 p-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="No. SJ atau nama pelanggan..."
                class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
        </div>
        <select name="status" class="px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <option value="">Semua Status</option>
            <option value="dibuat" {{ request('status')=='dibuat' ? 'selected' : '' }}>Dibuat</option>
            <option value="dikirim" {{ request('status')=='dikirim' ? 'selected' : '' }}>Dikirim</option>
            <option value="selesai" {{ request('status')=='selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <button type="submit" class="px-4 py-2.5 rounded-lg bg-neutral-900 dark:bg-primary-600 text-white text-sm font-medium">Filter</button>
        <a href="{{ route('admin.surat-jalan.index') }}" class="px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm text-neutral-600 dark:text-neutral-200 hover:bg-neutral-100">Reset</a>
    </form>
</div>

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-600">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">No. SJ</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Tanggal</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Pelanggan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">No. Permintaan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Driver</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @forelse($suratJalan as $sj)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600/40 transition">
                    <td class="px-5 py-3.5">
                        <span class="font-bold text-primary-600 text-sm">{{ $sj->no_sj }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-sm dark:text-white">{{ $sj->tanggal->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-sm dark:text-white mb-0">{{ $sj->permintaan->pelanggan->name ?? '-' }}</p>
                        <p class="text-xs text-secondary-light mb-0">{{ $sj->permintaan->pelanggan->phone ?? '' }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-secondary-light font-mono">{{ $sj->permintaan->no_permintaan ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-sm dark:text-white">{{ $sj->driver ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-center">
                        @php $color = ['dibuat'=>'warning','dikirim'=>'info','selesai'=>'success'][$sj->status] ?? 'secondary'; @endphp
                        <span class="px-2.5 py-1 rounded-full bg-{{ $color }}-100 text-{{ $color }}-700 text-xs font-semibold">{{ $sj->status_label }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.surat-jalan.show', $sj) }}" class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 hover:bg-primary-200 inline-flex items-center justify-center" title="Detail">
                                <iconify-icon icon="ri:eye-line"></iconify-icon>
                            </a>
                            <a href="{{ route('admin.surat-jalan.print', $sj) }}" target="_blank" class="w-8 h-8 rounded-lg bg-neutral-100 text-neutral-600 hover:bg-neutral-200 dark:bg-neutral-600 dark:text-neutral-200 inline-flex items-center justify-center" title="Cetak">
                                <iconify-icon icon="ri:printer-line"></iconify-icon>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-secondary-light">Belum ada surat jalan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4">{{ $suratJalan->links() }}</div>
</div>
@endsection
