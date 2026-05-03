@extends('layouts.app')
@section('title', 'Detail Pengeluaran')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Detail Pengeluaran</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">{{ $pengeluaran->keterangan }}</p>
    </div>
    <a href="{{ route('admin.pengeluaran.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 inline-flex items-center gap-2">
        <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali ke daftar
    </a>
</div>

@include('partials.alert')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6">
            <h6 class="font-semibold text-sm text-secondary-light uppercase tracking-wide mb-4">Ringkasan</h6>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <dt class="text-secondary-light mb-1">Tanggal</dt>
                    <dd class="font-semibold dark:text-white mb-0">{{ $pengeluaran->tanggal->format('d F Y') }}</dd>
                </div>
                <div>
                    <dt class="text-secondary-light mb-1">Kategori</dt>
                    <dd class="mb-0">
                        <span class="px-2.5 py-1 rounded-full bg-warning-100 text-warning-700 text-xs font-semibold">{{ $pengeluaran->kategori }}</span>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-secondary-light mb-1">Keterangan</dt>
                    <dd class="font-medium dark:text-white mb-0">{{ $pengeluaran->keterangan }}</dd>
                </div>
                <div>
                    <dt class="text-secondary-light mb-1">Nominal</dt>
                    <dd class="text-xl font-bold text-red-500 mb-0">Rp {{ number_format($pengeluaran->nominal, 0, ',', '.') }}</dd>
                </div>
                @if($pengeluaran->pembuat)
                <div>
                    <dt class="text-secondary-light mb-1">Dicatat oleh</dt>
                    <dd class="font-medium dark:text-white mb-0">{{ $pengeluaran->pembuat->name }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6">
            <h6 class="font-semibold text-sm text-secondary-light uppercase tracking-wide mb-4">Bukti</h6>
            @if($pengeluaran->bukti_foto)
            <div class="rounded-xl overflow-hidden border border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <img src="{{ Storage::url($pengeluaran->bukti_foto) }}" alt="Bukti pengeluaran" class="w-full max-h-[520px] object-contain mx-auto">
            </div>
            <a href="{{ Storage::url($pengeluaran->bukti_foto) }}" target="_blank" rel="noopener"
                class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700">
                <iconify-icon icon="ri:external-link-line"></iconify-icon> Buka gambar di tab baru
            </a>
            @else
            <p class="text-secondary-light text-sm mb-0">Tidak ada lampiran bukti untuk pengeluaran ini.</p>
            @endif
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6">
            <h6 class="font-semibold text-sm mb-4 dark:text-white">Aksi</h6>
            <form action="{{ route('admin.pengeluaran.destroy', $pengeluaran) }}" method="POST" onsubmit="return confirm('Hapus pengeluaran ini beserta bukti?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2.5 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 text-sm font-medium flex items-center justify-center gap-2">
                    <iconify-icon icon="ri:delete-bin-line"></iconify-icon> Hapus pengeluaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
