@extends('layouts.app')
@section('title', 'Mutasi Stok - ' . $barang->nama)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Mutasi Stok</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">{{ $barang->kode }} — {{ $barang->nama }}</p>
    </div>
    <a href="{{ route('gudang.stok.index') }}"
        class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-1.5">
        <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    @php $stokJml = $barang->stok?->jumlah ?? 0; @endphp
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 text-center">
        <p class="text-xs text-secondary-light font-medium mb-1">Stok Saat Ini</p>
        <h4 class="font-bold text-2xl mb-1 {{ $stokJml <= $barang->stok_minimum ? 'text-red-500' : 'text-success-600' }}">{{ number_format($stokJml, 0, ',', '.') }}</h4>
        <p class="text-xs text-secondary-light mb-0">{{ $barang->satuan }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 text-center">
        <p class="text-xs text-secondary-light font-medium mb-1">Harga Rata-rata</p>
        <h5 class="font-bold dark:text-white mb-0">Rp {{ number_format($barang->stok?->harga_rata ?? 0, 0, ',', '.') }}</h5>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 text-center">
        <p class="text-xs text-secondary-light font-medium mb-1">Nilai Stok</p>
        <h5 class="font-bold text-primary-600 mb-0">Rp {{ number_format($stokJml * ($barang->stok?->harga_rata ?? 0), 0, ',', '.') }}</h5>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 text-center">
        <p class="text-xs text-secondary-light font-medium mb-1">Stok Minimum</p>
        <h5 class="font-bold dark:text-white mb-0">{{ $barang->stok_minimum }} <span class="text-sm font-normal text-secondary-light">{{ $barang->satuan }}</span></h5>
    </div>
</div>

{{-- Mutasi Table --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:history-line" class="text-info-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Riwayat Mutasi Stok</h6>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Tanggal</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Tipe</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Jumlah</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Harga Satuan</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Referensi</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Keterangan</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Oleh</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @forelse($mutasi as $m)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                    <td class="px-6 py-3.5 text-xs text-secondary-light whitespace-nowrap">{{ $m->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-3.5">
                        @if($m->tipe === 'masuk')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-success-100 text-success-600 text-xs font-semibold">
                            <iconify-icon icon="ri:arrow-down-circle-line" class="text-xs"></iconify-icon> Masuk
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-100 text-red-500 text-xs font-semibold">
                            <iconify-icon icon="ri:arrow-up-circle-line" class="text-xs"></iconify-icon> Keluar
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-3.5 text-right font-bold text-sm {{ $m->tipe === 'masuk' ? 'text-success-600' : 'text-red-500' }} whitespace-nowrap">
                        {{ $m->tipe === 'masuk' ? '+' : '-' }}{{ number_format($m->jumlah, 0, ',', '.') }} {{ $barang->satuan }}
                    </td>
                    <td class="px-6 py-3.5 text-right text-xs text-secondary-light whitespace-nowrap">Rp {{ number_format($m->harga_satuan, 0, ',', '.') }}</td>
                    <td class="px-6 py-3.5 text-xs {{ $m->referensi ? 'text-primary-600 font-semibold' : 'text-neutral-400' }}">{{ $m->referensi ?? '—' }}</td>
                    <td class="px-6 py-3.5 text-xs text-secondary-light">{{ $m->keterangan ?? '—' }}</td>
                    <td class="px-6 py-3.5 text-xs text-secondary-light">{{ $m->user->name }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <iconify-icon icon="ri:history-line" class="text-3xl text-neutral-300 block mx-auto mb-2"></iconify-icon>
                        <p class="text-sm text-secondary-light mb-0">Belum ada riwayat mutasi</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($mutasi->hasPages())
    <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-600">{{ $mutasi->links() }}</div>
    @endif
</div>
@endsection
