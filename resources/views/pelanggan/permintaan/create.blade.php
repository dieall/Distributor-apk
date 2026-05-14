@extends('layouts.app')
@section('title', 'Buat Permintaan Barang')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Buat Permintaan Barang</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Pilih barang yang Anda butuhkan</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('pelanggan.permintaan.index') }}" class="hover:text-primary-600">Permintaan</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Buat Baru</li>
    </ul>
</div>

@include('partials.alert')

<form action="{{ route('pelanggan.permintaan.store') }}" method="POST">
@csrf

{{-- Info Permintaan --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:information-line" class="text-primary-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Informasi Permintaan</h6>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal Dibutuhkan</label>
                <input type="date" name="tanggal_dibutuhkan"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                <p class="text-neutral-400 text-xs mt-1">Kosongkan jika tidak ada batas waktu</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">No. PO Customer</label>
                <input type="text" name="no_po_customer" value="{{ old('no_po_customer') }}" maxlength="50" placeholder="Nomor PO pembelian Anda (opsional)"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                <p class="text-neutral-400 text-xs mt-1">Akan tampil otomatis di pengaturan cetak invoice</p>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Catatan</label>
                <textarea name="catatan" rows="3" placeholder="Instruksi khusus, catatan pengiriman, dll..."
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
            </div>
        </div>
    </div>
</div>

{{-- Katalog Barang --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-2">
            <iconify-icon icon="ri:shopping-bag-3-line" class="text-pink-500 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Pilih Barang</h6>
        </div>
        <p class="text-secondary-light text-xs mb-0">Centang barang &amp; isi jumlah. <span class="text-neutral-500">Harga MBG dari admin.</span></p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm table-fixed min-w-[720px]" id="tabel-katalog">
            <colgroup>
                <col class="w-12">
                <col class="w-[28%]">
                <col class="w-[14%]">
                <col class="w-20">
                <col class="w-28">
                <col class="w-28">
                <col class="w-32">
            </colgroup>
            <thead>
                <tr class="bg-neutral-100 dark:bg-neutral-800/90 border-b border-neutral-200 dark:border-neutral-600">
                    <th class="px-3 py-3 text-center align-middle">
                        <input type="checkbox" id="check-all" class="w-4 h-4 rounded border-neutral-300 text-primary-600 cursor-pointer" title="Pilih semua">
                    </th>
                    <th class="text-left px-3 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide align-middle">Nama Barang</th>
                    <th class="text-left px-3 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide align-middle">Kategori</th>
                    <th class="text-left px-3 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide align-middle">Satuan</th>
                    <th class="text-right px-3 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide align-middle">Harga</th>
                    <th class="text-right px-3 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide align-middle">Harga MBG</th>
                    <th class="text-center px-3 py-3 text-xs font-semibold text-secondary-light uppercase tracking-wide align-middle">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @forelse($barang as $b)
                @php
                    $hargaMbg = $b->harga_mbg ?? $b->harga_jual;
                @endphp
                <tr class="hover:bg-neutral-50/80 dark:hover:bg-neutral-600/40 transition-colors align-middle">
                    <td class="px-3 py-3 text-center">
                        <input type="checkbox" name="barang_id[]" value="{{ $b->id }}" class="check-item w-4 h-4 rounded border-neutral-300 text-primary-600 cursor-pointer">
                    </td>
                    <td class="px-3 py-3">
                        <p class="font-semibold text-sm dark:text-white mb-0 leading-snug">{{ $b->nama }}</p>
                        <p class="text-xs text-secondary-light mb-0 font-mono">{{ $b->kode }}</p>
                    </td>
                    <td class="px-3 py-3 text-secondary-light text-xs sm:text-sm leading-tight">{{ $b->kategori }}</td>
                    <td class="px-3 py-3 text-secondary-light text-sm whitespace-nowrap">{{ $b->satuan }}</td>
                    <td class="px-3 py-3 text-right text-sm font-semibold text-success-600 whitespace-nowrap">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                    <td class="px-3 py-3 text-right text-sm font-semibold text-primary-600 whitespace-nowrap">Rp {{ number_format($hargaMbg, 0, ',', '.') }}</td>
                    <td class="px-3 py-3">
                        <input type="number" name="jumlah_diminta[{{ $b->id }}]" value="1" min="0.01" step="0.01"
                            class="input-jumlah w-full max-w-[7rem] mx-auto block px-2.5 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm text-center bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500" disabled>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center">
                        <iconify-icon icon="ri:inbox-line" class="text-3xl text-neutral-300 block mx-auto mb-2"></iconify-icon>
                        <p class="text-sm text-secondary-light mb-0">Tidak ada barang tersedia</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="flex items-center justify-end gap-3">
    <a href="{{ route('pelanggan.permintaan.index') }}"
        class="px-5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-2">
        <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Batal
    </a>
    <button type="submit"
        class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition flex items-center gap-2 shadow-sm">
        <iconify-icon icon="ri:send-plane-line"></iconify-icon> Kirim Permintaan
    </button>
</div>

</form>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.check-item').forEach(cb => {
    cb.addEventListener('change', function() {
        const inp = this.closest('tr').querySelector('.input-jumlah');
        inp.disabled = !this.checked;
        if (this.checked) inp.focus();
    });
});
document.getElementById('check-all')?.addEventListener('change', function() {
    document.querySelectorAll('.check-item').forEach(cb => {
        cb.checked = this.checked;
        cb.dispatchEvent(new Event('change'));
    });
});
</script>
@endpush
