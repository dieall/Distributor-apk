@extends('layouts.sawit')
@section('title', 'Detail Surat Jalan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div><h6 class="font-semibold mb-0 dark:text-white">Detail Surat Jalan: {{ $suratJalan->no_surat_jalan }}</h6></div>
    <div class="flex items-center gap-2">
        <a href="{{ route('sawit.admin.surat-jalan.print', $suratJalan) }}" target="_blank" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition flex items-center gap-2"><iconify-icon icon="ri:printer-line"></iconify-icon> Cetak</a>
        <a href="{{ route('sawit.admin.surat-jalan.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition flex items-center gap-2"><iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali</a>
    </div>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Informasi Surat Jalan</h6>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-y-5 gap-x-4">
            <div><p class="text-xs text-secondary-light mb-1">No Surat Jalan</p><p class="text-sm font-bold dark:text-white">{{ $suratJalan->no_surat_jalan }}</p></div>
            <div><p class="text-xs text-secondary-light mb-1">No Invoice</p><p class="text-sm font-semibold dark:text-white">{{ $suratJalan->penjualan->no_invoice }}</p></div>
            <div><p class="text-xs text-secondary-light mb-1">Tanggal</p><p class="text-sm font-semibold dark:text-white">{{ $suratJalan->tanggal->format('d F Y') }}</p></div>
            <div><p class="text-xs text-secondary-light mb-1">Nama Perusahaan / Tujuan</p><p class="text-sm font-semibold dark:text-white">{{ $suratJalan->nama_perusahaan }}</p></div>
            <div class="md:col-span-2"><p class="text-xs text-secondary-light mb-1">Alamat Tujuan</p><p class="text-sm font-semibold dark:text-white">{{ $suratJalan->alamat_tujuan }}</p></div>
            <div><p class="text-xs text-secondary-light mb-1">Jenis Sawit</p><p class="text-sm font-semibold dark:text-white">{{ $suratJalan->jenis_sawit }}</p></div>
            <div><p class="text-xs text-secondary-light mb-1">Total Kg</p><p class="text-lg font-bold text-success-600">{{ number_format($suratJalan->total_kg, 2) }} Kg</p></div>
            <div><p class="text-xs text-secondary-light mb-1">Jenis Kendaraan</p><p class="text-sm font-semibold dark:text-white">{{ $suratJalan->jenis_kendaraan }}</p></div>
            <div><p class="text-xs text-secondary-light mb-1">No Mobil / Plat</p><p class="text-sm font-semibold dark:text-white">{{ $suratJalan->no_mobil }}</p></div>
            <div><p class="text-xs text-secondary-light mb-1">Nama Supir</p><p class="text-sm font-semibold dark:text-white">{{ $suratJalan->nama_supir }}</p></div>
            @if($suratJalan->keterangan)
            <div class="md:col-span-3"><p class="text-xs text-secondary-light mb-1">Keterangan</p><p class="text-sm dark:text-white">{{ $suratJalan->keterangan }}</p></div>
            @endif
            <div><p class="text-xs text-secondary-light mb-1">Dibuat Oleh</p><p class="text-sm dark:text-white">{{ $suratJalan->creator->name ?? '-' }}</p></div>
            <div><p class="text-xs text-secondary-light mb-1">Dibuat Pada</p><p class="text-sm dark:text-white">{{ $suratJalan->created_at->format('d/m/Y H:i') }}</p></div>
        </div>
    </div>
</div>
@endsection
