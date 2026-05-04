@extends('layouts.app')
@section('title', 'Catat Pengeluaran Barang')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Catat Pengeluaran Barang</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Stok akan berkurang sesuai jumlah yang Anda isi</p>
    </div>
    <a href="{{ route('gudang.pengeluaran-barang.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 flex items-center gap-2">
        <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali
    </a>
</div>

@include('partials.alert')

<form action="{{ route('gudang.pengeluaran-barang.store') }}" method="POST">
    @csrf

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
            <iconify-icon icon="ri:file-info-line" class="text-primary-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Informasi</h6>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Alasan <span class="text-red-500">*</span></label>
                <select name="alasan" required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">— Pilih alasan —</option>
                    @foreach($alasanOpsi as $key => $label)
                    <option value="{{ $key }}" {{ old('alasan') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Catatan (opsional)</label>
                <textarea name="catatan" rows="2" placeholder="Penjelasan singkat"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">{{ old('catatan') }}</textarea>
            </div>
        </div>
    </div>

    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <iconify-icon icon="ri:logout-box-r-line" class="text-danger-500 text-lg"></iconify-icon>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Detail barang keluar</h6>
            </div>
            <p class="text-secondary-light text-xs mb-0">Minimal satu baris terisi. Jumlah tidak boleh melebihi stok.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-neutral-50 dark:bg-neutral-800">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Barang</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase" style="min-width:120px">Jumlah keluar</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Keterangan baris</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                    @for($i = 0; $i < 15; $i++)
                    <tr>
                        <td class="px-5 py-2.5">
                            <select name="barang_id[]"
                                class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                                <option value="">— Pilih barang —</option>
                                @foreach($barang as $b)
                                @php $sj = $b->stok?->jumlah ?? 0; @endphp
                                <option value="{{ $b->id }}" @selected(old('barang_id.'.$i, '') == $b->id)>
                                    {{ $b->kode }} — {{ $b->nama }} (stok: {{ format_qty_id($sj) }} {{ $b->satuan }})
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-5 py-2.5">
                            <input type="number" name="jumlah_keluar[]" value="{{ old('jumlah_keluar.'.$i, '') }}" min="0" step="0.01" placeholder="0"
                                class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </td>
                        <td class="px-5 py-2.5">
                            <input type="text" name="keterangan_detail[]" value="{{ old('keterangan_detail.'.$i) }}" placeholder="Opsional"
                                class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="px-6 py-2.5 rounded-lg bg-danger-600 hover:bg-danger-700 text-white text-sm font-medium flex items-center gap-2 shadow-sm">
            <iconify-icon icon="ri:save-line"></iconify-icon> Simpan &amp; kurangi stok
        </button>
    </div>
</form>
@endsection
