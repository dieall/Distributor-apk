@extends('layouts.app')
@section('title', 'Detail Surat Jalan ' . $suratJalan->no_sj)

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">{{ $suratJalan->no_sj }}</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Detail Surat Jalan Pengiriman</p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        @php $c = ['dibuat'=>'warning','dikirim'=>'info','selesai'=>'success'][$suratJalan->status] ?? 'secondary'; @endphp
        <span class="px-3 py-1.5 rounded-lg bg-{{ $c }}-100 text-{{ $c }}-700 text-sm font-semibold">{{ $suratJalan->status_label }}</span>
        <a href="{{ route('gudang.surat-jalan.print', $suratJalan) }}" target="_blank"
            class="px-4 py-2 rounded-lg bg-neutral-800 hover:bg-neutral-900 text-white text-sm font-medium flex items-center gap-2">
            <iconify-icon icon="ri:printer-line"></iconify-icon> Cetak
        </a>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.invoice.show', $suratJalan->permintaan) }}"
            class="px-4 py-2 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium flex items-center gap-2">
            <iconify-icon icon="ri:bill-line"></iconify-icon> Lihat Invoice
        </a>
        @endif
        <a href="{{ route('gudang.surat-jalan.index') }}"
            class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 flex items-center gap-2">
            <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali
        </a>
    </div>
</div>

@include('partials.alert')

<div class="w-full space-y-6">
    {{-- Informasi Pengiriman --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 w-full">
        <h6 class="font-semibold text-sm text-secondary-light uppercase tracking-wide mb-4">Informasi Pengiriman</h6>
        <dl class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 text-sm">
            <div>
                <dt class="text-secondary-light mb-1">No. Surat Jalan</dt>
                <dd class="font-bold text-primary-600 mb-0">{{ $suratJalan->no_sj }}</dd>
            </div>
            <div>
                <dt class="text-secondary-light mb-1">Tanggal</dt>
                <dd class="font-semibold dark:text-white mb-0">{{ $suratJalan->tanggal->format('d F Y') }}</dd>
            </div>
            <div>
                <dt class="text-secondary-light mb-1">No. Permintaan</dt>
                <dd class="font-semibold dark:text-white mb-0 font-mono text-xs">{{ $suratJalan->permintaan->no_permintaan }}</dd>
            </div>
            <div>
                <dt class="text-secondary-light mb-1">Pelanggan</dt>
                <dd class="font-semibold dark:text-white mb-0">{{ $suratJalan->permintaan->pelanggan->name }}</dd>
            </div>
            <div>
                <dt class="text-secondary-light mb-1">Driver</dt>
                <dd class="font-semibold dark:text-white mb-0">{{ $suratJalan->driver ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-secondary-light mb-1">No. Kendaraan</dt>
                <dd class="font-semibold dark:text-white mb-0">{{ $suratJalan->no_kendaraan ?? '—' }}</dd>
            </div>
            @if($suratJalan->alamat_tujuan)
            <div class="col-span-2 sm:col-span-3 lg:col-span-4 xl:col-span-6">
                <dt class="text-secondary-light mb-1">Alamat Tujuan</dt>
                <dd class="font-medium dark:text-white mb-0">{{ $suratJalan->alamat_tujuan }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-secondary-light mb-1">Dibuat Oleh</dt>
                <dd class="font-semibold dark:text-white mb-0">{{ $suratJalan->dibuatOleh->name ?? '—' }}</dd>
            </div>
        </dl>
    </div>

    @if($suratJalan->catatan)
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 w-full">
        <p class="text-xs font-semibold text-secondary-light uppercase tracking-wide mb-2">Catatan</p>
        <p class="text-sm dark:text-white mb-0">{{ $suratJalan->catatan }}</p>
    </div>
    @endif

    {{-- Update Status --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6 w-full">
        <p class="text-xs font-semibold text-secondary-light uppercase tracking-wide mb-3">Update Status</p>
        <form action="{{ route('gudang.surat-jalan.status', $suratJalan) }}" method="POST" class="flex flex-col sm:flex-row sm:flex-wrap sm:items-end gap-3">
            @csrf @method('PATCH')
            <div class="w-full sm:w-auto sm:min-w-[220px]">
                <select name="status" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @foreach(['dibuat'=>'Dibuat','dikirim'=>'Dikirim','selesai'=>'Selesai'] as $val => $lbl)
                    <option value="{{ $val }}" {{ $suratJalan->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium">
                Simpan Status
            </button>
        </form>
    </div>

    {{-- Barang Dikirim — lebar penuh --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
            <iconify-icon icon="ri:box-3-line" class="text-success-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Barang Dikirim</h6>
        </div>
        <div class="w-full overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-neutral-50 dark:bg-neutral-800">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase w-[40%] sm:w-auto">Barang</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Jumlah</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Harga</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                    @php $total = 0; @endphp
                    @foreach($suratJalan->detail as $d)
                    @php
                        $pDetail = $suratJalan->permintaan->detail->firstWhere('barang_id', $d->barang_id);
                        $harga = $pDetail->harga_jual ?? 0;
                        $sub   = $d->jumlah * $harga;
                        $total += $sub;
                    @endphp
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600/40">
                        <td class="px-5 py-3">
                            <p class="font-semibold text-sm dark:text-white mb-0">{{ $d->barang->nama }}</p>
                            <p class="text-xs text-secondary-light mb-0">{{ $d->barang->kode }}</p>
                        </td>
                        <td class="px-5 py-3 text-right text-sm dark:text-white whitespace-nowrap">{{ format_qty_id($d->jumlah) }} {{ $d->barang->satuan }}</td>
                        <td class="px-5 py-3 text-right text-sm dark:text-white whitespace-nowrap">Rp {{ number_format($harga,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right font-bold text-success-600 whitespace-nowrap">Rp {{ number_format($sub,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-neutral-50 dark:bg-neutral-800">
                        <td colspan="3" class="px-5 py-3 text-right font-bold text-sm dark:text-white">Total</td>
                        <td class="px-5 py-3 text-right font-bold text-success-600 text-base whitespace-nowrap">Rp {{ number_format($total,0,',','.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
