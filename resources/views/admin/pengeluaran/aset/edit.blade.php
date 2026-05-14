@extends('layouts.app')
@section('title', 'Jadwal Aset — '.$aset->keterangan)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Penyusutan aset</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">{{ $aset->keterangan }} · Total Rp {{ number_format($aset->total_nilai, 0, ',', '.') }} · {{ $aset->jumlah_bulan }} bulan</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ fin_route('pengeluaran.aset.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100">Daftar aset</a>
        <a href="{{ fin_route('pengeluaran.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100">Pengeluaran</a>
    </div>
</div>

@include('partials.alert')

<div class="w-full space-y-6">
    <div class="lg:col-span-2 card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6">
        <h6 class="font-semibold text-sm text-secondary-light uppercase tracking-wide mb-4">Bulan penyusutan</h6>
        <p class="text-sm text-secondary-light mb-4">Centang bulan yang <strong>akan</strong> diposting ke pengeluaran. Bulan yang sudah terposting tidak bisa diubah. Scheduler harian (06:00) memasukkan cicilan yang tanggalnya sudah lewat; setelah simpan, sistem juga langsung memeriksa jatuh tempo.</p>

        @if($readonly ?? false)
        <p class="text-sm text-secondary-light mb-4">Anda masuk sebagai direktur (hanya lihat).</p>
        @endif

        <form action="{{ fin_route('pengeluaran.aset.update', $aset) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="overflow-x-auto border border-neutral-200 dark:border-neutral-600 rounded-lg">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-neutral-50 dark:bg-neutral-800">
                            <th class="text-center px-3 py-2 w-14">Aktif</th>
                            <th class="text-left px-3 py-2">Ke-</th>
                            <th class="text-left px-3 py-2">Tanggal</th>
                            <th class="text-right px-3 py-2">Nominal</th>
                            <th class="text-left px-3 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                        @foreach($aset->cicilans as $c)
                        <tr class="{{ $c->pengeluaran_id ? 'bg-neutral-50/80 dark:bg-neutral-800/50' : '' }}">
                            <td class="px-3 py-2 text-center align-middle">
                                @if($c->pengeluaran_id)
                                <input type="checkbox" checked disabled class="w-4 h-4 rounded border-neutral-300 text-primary-600" title="Sudah diposting">
                                @elseif($readonly ?? false)
                                <span class="text-xs {{ $c->is_aktif ? 'text-success-600' : 'text-neutral-400' }}">{{ $c->is_aktif ? 'Ya' : 'Tidak' }}</span>
                                @else
                                <input type="checkbox" name="aktif[{{ $c->id }}]" value="1" class="w-4 h-4 rounded border-neutral-300 text-primary-600" {{ $c->is_aktif ? 'checked' : '' }}>
                                @endif
                            </td>
                            <td class="px-3 py-2 font-mono text-xs">{{ $c->urutan }} / {{ $aset->jumlah_bulan }}</td>
                            <td class="px-3 py-2">{{ $c->tanggal->format('d M Y') }}</td>
                            <td class="px-3 py-2 text-right font-semibold">Rp {{ number_format($c->nominal, 0, ',', '.') }}</td>
                            <td class="px-3 py-2">
                                @if($c->pengeluaran_id && $c->pengeluaran)
                                <a href="{{ fin_route('pengeluaran.show', $c->pengeluaran) }}" class="text-xs text-primary-600 hover:underline">Lihat pengeluaran</a>
                                @elseif($c->is_aktif)
                                <span class="text-xs text-warning-600">Menunggu tanggal / jadwal</span>
                                @else
                                <span class="text-xs text-neutral-400">Dilewati (tidak dicentang)</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @unless($readonly ?? false)
            <button type="submit" class="mt-4 px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium inline-flex items-center gap-2">
                <iconify-icon icon="ri:save-line"></iconify-icon> Simpan centang bulan
            </button>
            @endunless
        </form>
    </div>

    <div class="space-y-6">
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6">
            <h6 class="font-semibold text-sm text-secondary-light uppercase tracking-wide mb-3">Ringkasan</h6>
            <dl class="text-sm space-y-2">
                <div class="flex justify-between gap-2"><dt class="text-secondary-light">Sisa nilai belum terposting</dt><dd class="font-bold text-red-500">Rp {{ number_format($aset->sisa_nilai, 0, ',', '.') }}</dd></div>
                <div class="flex justify-between gap-2"><dt class="text-secondary-light">Dicatat oleh</dt><dd class="dark:text-white">{{ $aset->dibuatOleh->name ?? '—' }}</dd></div>
            </dl>
        </div>
        @if($aset->bukti_foto)
        @php
            $rawPath = trim((string) $aset->bukti_foto);
            $normalizedPath = str_replace('\\', '/', ltrim($rawPath, '/'));
            if (str_starts_with($normalizedPath, 'storage/')) {
                $normalizedPath = substr($normalizedPath, 8);
            }
            if (str_starts_with($normalizedPath, 'public/')) {
                $normalizedPath = substr($normalizedPath, 7);
            }
            $buktiUrl = fin_route('pengeluaran.bukti', ['path' => $normalizedPath]);
        @endphp
        <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6">
            <h6 class="font-semibold text-sm text-secondary-light uppercase tracking-wide mb-3">Bukti pembelian aset</h6>
            <img src="{{ $buktiUrl }}" alt="Bukti" class="w-full rounded-lg border border-neutral-200 max-h-48 object-contain">
            <a href="{{ $buktiUrl }}" target="_blank" class="mt-2 inline-block text-xs text-primary-600 hover:underline">Buka penuh</a>
        </div>
        @endif
    </div>
</div>
@endsection
