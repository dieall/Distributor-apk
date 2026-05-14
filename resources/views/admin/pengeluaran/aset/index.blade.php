@extends('layouts.app')
@section('title', 'Penyusutan Aset')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Penyusutan / Cicilan Aset</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Pengeluaran aset dibagi per bulan; atur centang bulan di halaman edit.</p>
    </div>
    <a href="{{ fin_route('pengeluaran.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100">Kembali ke pengeluaran</a>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Keterangan</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Total nilai</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Bulan</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Mulai</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Terposting</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @forelse($aset as $a)
                <tr>
                    <td class="px-6 py-3.5 dark:text-white font-medium">{{ $a->keterangan }}</td>
                    <td class="px-6 py-3.5 text-right font-semibold text-red-500">Rp {{ number_format($a->total_nilai, 0, ',', '.') }}</td>
                    <td class="px-6 py-3.5 text-center">{{ $a->jumlah_bulan }}</td>
                    <td class="px-6 py-3.5 text-secondary-light">{{ $a->tanggal_mulai->format('d/m/Y') }}</td>
                    <td class="px-6 py-3.5 text-center">
                        <span class="text-xs font-semibold">{{ $a->cicilan_terposting }} / {{ $a->cicilan_total }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        <a href="{{ fin_route('pengeluaran.aset.edit', $a) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary-100 text-primary-700 text-xs font-medium hover:bg-primary-200">
                            <iconify-icon icon="ri:calendar-check-line"></iconify-icon> Jadwal &amp; centang
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-secondary-light">Belum ada aset dengan penyusutan. Tambah dari menu Pengeluaran &rarr; kategori Aset.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">{{ $aset->links() }}</div>
</div>
@endsection
