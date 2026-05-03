@extends('layouts.app')
@section('title', 'Detail Permintaan - ' . $permintaan->no_permintaan)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">{{ $permintaan->no_permintaan }}</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Detail & Proses Permintaan Barang</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="px-3 py-1.5 rounded-lg bg-{{ $permintaan->status_color }}-100 text-{{ $permintaan->status_color }}-600 text-sm font-semibold">
            {{ $permintaan->status_label }}
        </span>
        <a href="{{ route('sales.permintaan.index') }}"
            class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-1.5">
            <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali
        </a>
    </div>
</div>

@include('partials.alert')

{{-- Info Bar --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Pelanggan</p>
        <p class="font-bold text-sm dark:text-white mb-0">{{ $permintaan->pelanggan->name }}</p>
        <p class="text-xs text-secondary-light mb-0">{{ $permintaan->pelanggan->email }}</p>
    </div>
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Tanggal Permintaan</p>
        <p class="font-medium text-sm dark:text-white mb-0">{{ $permintaan->tanggal_request->format('d M Y') }}</p>
    </div>
    @if($permintaan->tanggal_dibutuhkan)
    <div class="card shadow-none border border-warning-200 bg-warning-50 dark:bg-neutral-700 dark:border-neutral-600 rounded-xl p-4">
        <p class="text-xs text-warning-600 font-medium mb-1">Dibutuhkan Sebelum</p>
        <p class="font-bold text-warning-600 text-sm mb-0">{{ $permintaan->tanggal_dibutuhkan->format('d M Y') }}</p>
    </div>
    @endif
    @if($permintaan->diprosesOleh)
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <p class="text-xs text-secondary-light font-medium mb-1">Diproses Oleh</p>
        <p class="font-medium text-sm dark:text-white mb-0">{{ $permintaan->diprosesOleh->name }}</p>
    </div>
    @endif
</div>

@if($permintaan->catatan)
<div class="mb-6 p-4 rounded-xl border border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-start gap-3">
    <iconify-icon icon="ri:sticky-note-line" class="text-neutral-500 text-lg flex-shrink-0"></iconify-icon>
    <p class="text-sm text-neutral-700 dark:text-neutral-200 mb-0"><strong>Catatan Pelanggan:</strong> {{ $permintaan->catatan }}</p>
</div>
@endif

{{-- Surat Jalan Info --}}
@if($permintaan->suratJalan)
<div class="card shadow-none border border-success-200 bg-success-50 dark:bg-neutral-700 dark:border-neutral-600 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-success-200 dark:border-neutral-600 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <iconify-icon icon="ri:truck-line" class="text-success-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 text-success-700 text-sm">Surat Jalan Aktif</h6>
        </div>
        <span class="px-2.5 py-1 rounded-full bg-{{ $permintaan->suratJalan->status_color }}-100 text-{{ $permintaan->suratJalan->status_color }}-600 text-xs font-semibold">
            {{ $permintaan->suratJalan->status_label }}
        </span>
    </div>
    <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div>
            <p class="text-xs text-secondary-light font-medium mb-1">No. Surat Jalan</p>
            <p class="font-bold text-success-700 text-sm mb-0">{{ $permintaan->suratJalan->no_sj }}</p>
        </div>
        <div>
            <p class="text-xs text-secondary-light font-medium mb-1">Tanggal</p>
            <p class="font-medium text-sm dark:text-white mb-0">{{ $permintaan->suratJalan->tanggal->format('d M Y') }}</p>
        </div>
        @if($permintaan->suratJalan->driver)
        <div>
            <p class="text-xs text-secondary-light font-medium mb-1">Driver</p>
            <p class="font-medium text-sm dark:text-white mb-0">{{ $permintaan->suratJalan->driver }}</p>
        </div>
        @endif
        @if($permintaan->suratJalan->no_kendaraan)
        <div>
            <p class="text-xs text-secondary-light font-medium mb-1">Kendaraan</p>
            <p class="font-bold text-sm dark:text-white mb-0">{{ $permintaan->suratJalan->no_kendaraan }}</p>
        </div>
        @endif
    </div>
    @if($permintaan->suratJalan->status !== 'selesai')
    <div class="px-5 pb-5">
        <form action="{{ route('sales.suratJalan.kirim', $permintaan->suratJalan) }}" method="POST">
            @csrf @method('PATCH')
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium flex items-center gap-2 transition shadow-sm">
                <iconify-icon icon="ri:send-plane-line"></iconify-icon> Tandai Dikirim
            </button>
        </form>
    </div>
    @endif
</div>
@endif

{{-- Form Ceklis / Tampilan Hasil --}}
@if(in_array($permintaan->status, ['pending','diproses']))
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <iconify-icon icon="ri:checkbox-multiple-line" class="text-primary-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Form Ceklis Barang</h6>
        </div>
        <p class="text-secondary-light text-xs mb-0">Centang & isi jumlah yang dapat dipenuhi</p>
    </div>
    <form action="{{ route('sales.permintaan.ceklis', $permintaan) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-neutral-50 dark:bg-neutral-800">
                        <th class="px-6 py-3 text-xs font-semibold text-secondary-light" style="width:48px">✓</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Barang</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Diminta</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Stok</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Disetujui</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Harga Jual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                    @foreach($permintaan->detail as $i => $d)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                        <td class="px-6 py-3.5 text-center">
                            <input type="hidden" name="items[{{ $i }}][id]" value="{{ $d->id }}">
                            <input type="checkbox" name="items[{{ $i }}][is_checked]" value="1" {{ $d->is_checked ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-neutral-300 text-primary-600 cursor-pointer">
                        </td>
                        <td class="px-6 py-3.5">
                            <p class="font-semibold text-sm dark:text-white mb-0">{{ $d->barang->nama }}</p>
                            <p class="text-xs text-secondary-light mb-0">{{ $d->barang->kode }} · {{ $d->barang->satuan }}</p>
                        </td>
                        <td class="px-6 py-3.5 text-right font-bold text-sm dark:text-white">{{ number_format($d->jumlah_diminta, 0, ',', '.') }} {{ $d->barang->satuan }}</td>
                        <td class="px-6 py-3.5 text-right">
                            @php $stok = $d->barang->stok?->jumlah ?? 0; @endphp
                            <span class="text-sm font-bold {{ $stok < $d->jumlah_diminta ? 'text-red-500' : 'text-success-600' }}">{{ number_format($stok, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <input type="number" name="items[{{ $i }}][jumlah_disetujui]"
                                value="{{ $d->jumlah_disetujui ?: $d->jumlah_diminta }}"
                                min="0" step="0.01" max="{{ $d->barang->stok?->jumlah ?? 0 }}"
                                class="w-24 px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </td>
                        <td class="px-6 py-3.5">
                            <input type="text" name="items[{{ $i }}][harga_jual]"
                                value="{{ $d->harga_jual > 0 ? number_format($d->harga_jual, 0, ',', '.') : number_format($d->barang->harga_jual, 0, ',', '.') }}"
                                class="input-ribuan w-32 px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-neutral-200 dark:border-neutral-600 flex justify-end">
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium flex items-center gap-2 transition shadow-sm">
                <iconify-icon icon="ri:save-line"></iconify-icon> Simpan Ceklis
            </button>
        </div>
    </form>
</div>
@else
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:list-check" class="text-primary-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Detail Barang yang Dipesan</h6>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Barang</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Diminta</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Disetujui</th>
                    <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Nilai Jual</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @foreach($permintaan->detail as $d)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600 transition">
                    <td class="px-6 py-3.5">
                        <p class="font-semibold text-sm dark:text-white mb-0">{{ $d->barang->nama }}</p>
                        <p class="text-xs text-secondary-light mb-0">{{ $d->barang->kode }}</p>
                    </td>
                    <td class="px-6 py-3.5 text-right text-sm dark:text-white">{{ number_format($d->jumlah_diminta, 0, ',', '.') }} {{ $d->barang->satuan }}</td>
                    <td class="px-6 py-3.5 text-right font-bold text-sm dark:text-white">{{ number_format($d->jumlah_disetujui, 0, ',', '.') }} {{ $d->barang->satuan }}</td>
                    <td class="px-6 py-3.5 text-right font-bold text-success-600">Rp {{ number_format($d->subtotal_jual, 0, ',', '.') }}</td>
                    <td class="px-6 py-3.5 text-center">
                        @if($d->is_checked)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-success-100 text-success-600 text-xs font-semibold">✓ Disetujui</span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-red-100 text-red-500 text-xs font-semibold">✗ Ditolak</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Form Buat Surat Jalan --}}
@if($permintaan->status === 'diproses' && !$permintaan->suratJalan)
<div class="card shadow-none border border-primary-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-primary-200 dark:border-neutral-600 bg-primary-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:file-text-line" class="text-primary-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 text-primary-700 dark:text-white text-sm">Buat Surat Jalan</h6>
    </div>
    <div class="p-6">
        <form action="{{ route('sales.permintaan.surat-jalan', $permintaan) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal Pengiriman <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">No. Kendaraan</label>
                    <input type="text" name="no_kendaraan" placeholder="Contoh: B 1234 ABC"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Driver</label>
                    <input type="text" name="driver" placeholder="Nama pengemudi"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Alamat Tujuan</label>
                    <input type="text" name="alamat_tujuan" value="{{ $permintaan->pelanggan->name }}" placeholder="Alamat pengiriman"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div class="sm:col-span-2 lg:col-span-4">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Catatan</label>
                    <textarea name="catatan" rows="2" placeholder="Catatan pengiriman (opsional)"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
                </div>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium flex items-center gap-2 transition shadow-sm">
                <iconify-icon icon="ri:file-text-line"></iconify-icon> Buat Surat Jalan & Kurangi Stok
            </button>
        </form>
    </div>
</div>
@endif
@endsection
