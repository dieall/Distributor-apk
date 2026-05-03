@extends('layouts.app')
@section('title', 'Daftar Invoice')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Invoice Pelanggan</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Daftar invoice berdasarkan permintaan barang</p>
    </div>
</div>

@include('partials.alert')

{{-- Filter --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl mb-5 p-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="No. permintaan atau nama pelanggan..."
                class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
        </div>
        <div class="min-w-[160px]">
            <select name="pelanggan_id" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
                <option value="">Semua Pelanggan</option>
                @foreach($pelanggans as $pl)
                <option value="{{ $pl->id }}" {{ request('pelanggan_id') == $pl->id ? 'selected' : '' }}>{{ $pl->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2.5 rounded-lg bg-neutral-900 dark:bg-primary-600 text-white text-sm font-medium">Filter</button>
        <a href="{{ route('admin.invoice.index') }}" class="px-4 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm text-neutral-600 dark:text-neutral-200 hover:bg-neutral-100">Reset</a>
    </form>
</div>

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-600">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">No. Permintaan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Pelanggan</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Tanggal</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">No. Surat Jalan</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Total</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @forelse($permintaan as $p)
                @php
                    $total = $p->detail->sum('subtotal_jual');
                    $color = ['siap_kirim'=>'primary','selesai'=>'success'][$p->status] ?? 'secondary';
                @endphp
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600/40 transition">
                    <td class="px-5 py-3.5">
                        <span class="font-bold text-primary-600 font-mono text-xs">{{ $p->no_permintaan }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-sm dark:text-white mb-0">{{ $p->pelanggan->name }}</p>
                        <p class="text-xs text-secondary-light mb-0">{{ $p->pelanggan->phone ?? '' }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-sm text-secondary-light">{{ $p->tanggal_request->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5">
                        @if($p->suratJalan)
                        <span class="font-mono text-xs text-info-600 font-bold">{{ $p->suratJalan->no_sj }}</span>
                        @else
                        <span class="text-secondary-light text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-right font-bold text-success-600">Rp {{ number_format($total,0,',','.') }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="px-2.5 py-1 rounded-full bg-{{ $color }}-100 text-{{ $color }}-700 text-xs font-semibold">{{ $p->status_label }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.invoice.show', $p) }}" class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 hover:bg-primary-200 inline-flex items-center justify-center" title="Detail Invoice">
                                <iconify-icon icon="ri:eye-line"></iconify-icon>
                            </a>
                            <a href="{{ route('admin.invoice.print', $p) }}" target="_blank" class="w-8 h-8 rounded-lg bg-neutral-100 text-neutral-600 hover:bg-neutral-200 dark:bg-neutral-600 dark:text-neutral-200 inline-flex items-center justify-center" title="Cetak Invoice">
                                <iconify-icon icon="ri:printer-line"></iconify-icon>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-secondary-light">Belum ada invoice.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4">{{ $permintaan->links() }}</div>
</div>
@endsection
