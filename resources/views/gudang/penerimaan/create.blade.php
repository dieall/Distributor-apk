@extends('layouts.app')
@section('title', 'Form Penerimaan Barang')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Form Penerimaan Barang</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Catat barang yang diterima dari supplier</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('gudang.penerimaan.index') }}" class="hover:text-primary-600">Penerimaan</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Form Penerimaan</li>
    </ul>
</div>

@include('partials.alert')

<form action="{{ route('gudang.penerimaan.store') }}" method="POST" id="form-penerimaan">
@csrf

{{-- Pilih PO & Info --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:file-list-3-line" class="text-primary-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Pilih Purchase Order</h6>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Purchase Order (PO) <span class="text-red-500">*</span></label>
                <select name="pembelian_id" id="select-po" required
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('pembelian_id') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }}">
                    <option value="">-- Pilih PO yang akan diterima --</option>
                    @foreach($pembelian as $po)
                    @php
                        $poItems = $po->detail->map(function($d) {
                            return [
                                'id'     => $d->barang_id,
                                'nama'   => $d->barang->nama,
                                'kode'   => $d->barang->kode,
                                'satuan' => $d->barang->satuan,
                                'jumlah' => $d->jumlah,
                                'harga'  => $d->harga_satuan,
                            ];
                        })->values()->toArray();
                    @endphp
                    <option value="{{ $po->id }}" {{ old('pembelian_id') == $po->id ? 'selected' : '' }}
                        data-items='{{ json_encode($poItems) }}'>
                        {{ $po->no_po }} - {{ $po->supplier->name }} ({{ $po->tanggal->format('d M Y') }})
                    </option>
                    @endforeach
                </select>
                @error('pembelian_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                @if($pembelian->isEmpty())
                <p class="text-warning-600 text-xs mt-1 flex items-center gap-1">
                    <iconify-icon icon="ri:alert-line"></iconify-icon> Tidak ada PO dengan status "Dikirim".
                </p>
                @endif
            </div>
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal Penerimaan <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Catatan</label>
                <textarea name="catatan" rows="2" placeholder="Kondisi barang, dll (opsional)"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none resize-none">{{ old('catatan') }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Detail Barang Diterima --}}
<div id="section-detail" style="display:none;" class="mb-6">
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="ri:inbox-archive-line" class="text-success-600 text-lg"></iconify-icon>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Input Barang yang Diterima</h6>
            </div>
            <p class="text-secondary-light text-xs mb-0">Isi jumlah & harga aktual yang diterima</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-neutral-50 dark:bg-neutral-800">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Barang</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Dipesan</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Jml Diterima</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Harga Satuan (Rp)</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody id="tbody-penerimaan" class="divide-y divide-neutral-100 dark:divide-neutral-600"></tbody>
            </table>
        </div>
    </div>
</div>

<div id="section-submit" style="display:none;" class="flex items-center justify-end gap-3">
    <a href="{{ route('gudang.penerimaan.index') }}"
        class="px-5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-2">
        <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Batal
    </a>
    <button type="submit"
        class="px-5 py-2.5 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition flex items-center gap-2 shadow-sm">
        <iconify-icon icon="ri:checkbox-circle-line"></iconify-icon> Simpan Penerimaan
    </button>
</div>

</form>
@endsection

@push('scripts')
<script>
document.getElementById('select-po').addEventListener('change', function() {
    const items = JSON.parse(this.options[this.selectedIndex].dataset.items || '[]');
    const tbody = document.getElementById('tbody-penerimaan');
    tbody.innerHTML = '';
    if (items.length > 0) {
        items.forEach(item => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-neutral-50 transition';
            row.innerHTML = `
                <td class="px-6 py-3">
                    <input type="hidden" name="barang_id[]" value="${item.id}">
                    <p class="font-semibold text-sm mb-0 dark:text-white">${item.nama}</p>
                    <p class="text-xs text-secondary-light mb-0">${item.kode}</p>
                </td>
                <td class="px-6 py-3 text-center text-sm text-secondary-light">${parseFloat(item.jumlah).toLocaleString('id-ID')} ${item.satuan}</td>
                <td class="px-6 py-3">
                    <div class="flex items-center gap-2">
                        <input type="number" name="jumlah_diterima[]" class="input-jml w-28 px-3 py-2 rounded-lg border border-neutral-300 text-sm bg-white focus:outline-none" min="0" value="${item.jumlah}" step="0.01">
                        <span class="text-xs text-secondary-light">${item.satuan}</span>
                    </div>
                </td>
                <td class="px-6 py-3">
                    <input type="text" name="harga_satuan[]" class="input-harga input-ribuan w-36 px-3 py-2 rounded-lg border border-neutral-300 text-sm bg-white focus:outline-none" min="0" value="${window.formatRibuan ? window.formatRibuan(item.harga) : item.harga}">
                </td>
                <td class="px-6 py-3 text-right font-bold text-sm sub-label">Rp 0</td>
            `;
            tbody.appendChild(row);
            const hitung = () => {
                const j = parseFloat(row.querySelector('.input-jml').value)||0;
                const h = parseFloat(row.querySelector('.input-harga').value.replace(/\./g, ''))||0;
                row.querySelector('.sub-label').textContent = 'Rp ' + Math.round(j*h).toLocaleString('id-ID');
            };
            hitung();
            row.querySelector('.input-jml').addEventListener('input', hitung);
            row.querySelector('.input-harga').addEventListener('input', hitung);
        });
        document.getElementById('section-detail').style.display = '';
        document.getElementById('section-submit').style.display = '';
    } else {
        document.getElementById('section-detail').style.display = 'none';
        document.getElementById('section-submit').style.display = 'none';
    }
});
</script>
@endpush
