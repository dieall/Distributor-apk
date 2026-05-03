@extends('layouts.app')
@section('title', 'Pengeluaran')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Pengeluaran Operasional</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Catat biaya harian untuk perhitungan keuntungan.</p>
    </div>
    <a href="{{ route('admin.pengeluaran.create') }}" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium flex items-center gap-2">
        <iconify-icon icon="ri:add-line"></iconify-icon> Tambah Pengeluaran
    </a>
</div>

@include('partials.alert')

<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <p class="text-secondary-light text-sm mb-1">Total Pengeluaran</p>
        <h4 class="font-bold text-xl dark:text-white mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h4>
    </div>
    <form method="GET" class="lg:col-span-3 card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input type="month" name="bulan" value="{{ request('bulan') }}" class="px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori/keterangan" class="px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <button class="px-4 py-2.5 rounded-lg bg-neutral-900 text-white dark:bg-primary-600 text-sm font-medium">Filter</button>
        </div>
    </form>
</div>

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Tanggal</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Kategori</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Keterangan</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Nominal</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @forelse($pengeluaran as $item)
                <tr>
                    <td class="px-6 py-3.5 dark:text-white">{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td class="px-6 py-3.5"><span class="px-2.5 py-1 rounded-full bg-warning-100 text-warning-700 text-xs font-semibold">{{ $item->kategori }}</span></td>
                    <td class="px-6 py-3.5 dark:text-white">{{ $item->keterangan }}</td>
                    <td class="px-6 py-3.5 text-right font-bold text-red-500">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                    <td class="px-6 py-3.5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.pengeluaran.show', $item) }}" class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 hover:bg-primary-200 inline-flex items-center justify-center" title="Detail">
                                <iconify-icon icon="ri:eye-line"></iconify-icon>
                            </a>
                            <form action="{{ route('admin.pengeluaran.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengeluaran ini?')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 rounded-lg bg-red-100 text-red-500 hover:bg-red-200 inline-flex items-center justify-center" type="submit" title="Hapus">
                                    <iconify-icon icon="ri:delete-bin-line"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-secondary-light">Belum ada data pengeluaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">{{ $pengeluaran->links() }}</div>
</div>
@endsection
