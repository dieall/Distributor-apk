@extends('layouts.app')
@section('title', 'Buat Purchase Order')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Buat Purchase Order</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Buat PO pembelian barang ke supplier</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('admin.pembelian.index') }}" class="hover:text-primary-600">Purchase Order</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Buat PO</li>
    </ul>
</div>

@include('partials.alert')

<form action="{{ route('admin.pembelian.store') }}" method="POST" id="form-po" enctype="multipart/form-data">
@csrf

{{-- Info PO --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:file-list-3-line" class="text-primary-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Informasi Pembelian</h6>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Supplier <span class="text-red-500">*</span></label>
                <select name="supplier_id" required
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 {{ $errors->has('supplier_id') ? 'border-red-400' : 'border-neutral-300 dark:border-neutral-500' }}">
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal PO <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 border-neutral-300 dark:border-neutral-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Estimasi Tanggal Terima</label>
                <input type="date" name="tanggal_kirim_estimasi" value="{{ old('tanggal_kirim_estimasi') }}"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 border-neutral-300 dark:border-neutral-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Catatan</label>
                <textarea name="catatan" rows="1" placeholder="Catatan pembelian (opsional)"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">{{ old('catatan') }}</textarea>
            </div>
        </div>
    </div>
</div>

{{-- Bukti Pembayaran --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-200 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:image-line" class="text-warning-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Bukti Pembayaran</h6>
        <span class="ml-auto text-xs text-secondary-light">Opsional — JPG, PNG, PDF maks. 5MB</span>
    </div>
    <div class="p-5">
        {{-- Placeholder (sebelum upload) --}}
        <div class="border-2 border-dashed border-neutral-300 dark:border-neutral-500 rounded-xl py-8 px-6 text-center cursor-pointer hover:border-warning-400 hover:bg-warning-50 dark:hover:bg-neutral-600 transition" id="drop-zone">
            <div id="upload-placeholder">
                <iconify-icon icon="ri:upload-cloud-2-line" class="text-warning-500 text-3xl mb-2 block mx-auto"></iconify-icon>
                <p class="text-sm font-medium text-neutral-700 dark:text-neutral-200 mb-0.5">Klik atau seret file ke sini</p>
                <p class="text-xs text-secondary-light mb-0">JPG, PNG, WebP, PDF · Maks. 5MB</p>
            </div>

            {{-- Preview setelah upload (compact horizontal) --}}
            <div id="upload-preview" class="hidden text-left bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-600 rounded-xl p-3 inline-block min-w-[280px]">
                <div class="flex items-center gap-4">
                    {{-- Thumbnail gambar --}}
                    <div id="preview-img-wrap" class="w-16 h-16 rounded-lg overflow-hidden border border-neutral-200 flex-shrink-0 bg-neutral-100 flex items-center justify-center">
                        <img id="preview-img" src="" alt="Preview" class="max-w-full max-h-full object-contain">
                    </div>
                    {{-- Icon PDF --}}
                    <div id="preview-pdf" class="hidden w-16 h-16 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                        <iconify-icon icon="ri:file-pdf-line" class="text-red-500 text-3xl"></iconify-icon>
                    </div>
                    {{-- File info --}}
                    <div class="flex-1 min-w-0 pr-4">
                        <p id="preview-name" class="text-sm font-semibold text-neutral-800 dark:text-white mb-0.5 truncate"></p>
                        <p id="preview-size" class="text-xs text-secondary-light mb-0"></p>
                        <p class="text-xs text-success-600 flex items-center gap-1 mt-1 mb-0 font-medium">
                            <iconify-icon icon="ri:checkbox-circle-fill"></iconify-icon> Siap diupload
                        </p>
                    </div>
                    {{-- Tombol hapus --}}
                    <button type="button" id="btn-remove-file" title="Hapus File"
                        class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center flex-shrink-0 transition-colors">
                        <iconify-icon icon="ri:close-line" class="text-lg"></iconify-icon>
                    </button>
                </div>
            </div>

            <input type="file" name="bukti_pembayaran" id="input-bukti" accept="image/*,.pdf" class="hidden">
        </div>
        @error('bukti_pembayaran')
        <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
            <iconify-icon icon="ri:error-warning-line"></iconify-icon> {{ $message }}
        </p>
        @enderror
    </div>
</div>

{{-- Detail Barang --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <iconify-icon icon="ri:shopping-cart-2-line" class="text-success-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Detail Barang</h6>
        </div>
        <button type="button" id="btn-add-row"
            class="px-4 py-2 rounded-lg border border-primary-300 text-primary-600 bg-primary-50 hover:bg-primary-100 text-sm font-medium flex items-center gap-1.5 transition">
            <iconify-icon icon="ri:add-line"></iconify-icon> Tambah Baris
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="tabel-barang">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase" style="width:35%">Barang</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase" style="width:15%">Jumlah</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase" style="width:20%">Harga Satuan (Rp)</th>
                    <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase" style="width:20%">Subtotal</th>
                    <th style="width:10%"></th>
                </tr>
            </thead>
            <tbody id="tbody-barang" class="divide-y divide-neutral-100 dark:divide-neutral-600">
                <tr class="row-barang">
                    <td class="px-5 py-3">
                        <select name="barang_id[]" class="select-barang w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang as $b)
                            <option value="{{ $b->id }}" data-harga="{{ $b->harga_jual }}" data-satuan="{{ $b->satuan }}">{{ $b->kode }} - {{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <input type="number" name="jumlah[]" class="input-jumlah w-24 px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none" min="1" value="1" required>
                            <span class="text-xs text-secondary-light satuan-label"></span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <input type="text" name="harga_satuan[]" class="input-harga input-ribuan w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none" min="0" value="0" required>
                    </td>
                    <td class="px-5 py-3 text-right font-bold text-sm dark:text-white subtotal-label">Rp 0</td>
                    <td class="px-5 py-3 text-center">
                        <button type="button" class="btn-remove-row w-8 h-8 rounded-lg bg-red-100 text-red-500 hover:bg-red-200 flex items-center justify-center transition">
                            <iconify-icon icon="ri:delete-bin-line" class="text-sm"></iconify-icon>
                        </button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <td colspan="3" class="px-5 py-3 text-right font-bold text-sm dark:text-white">Total</td>
                    <td class="px-5 py-3 text-right font-bold text-primary-600 text-base" id="grand-total">Rp 0</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="flex items-center justify-end gap-3">
    <a href="{{ route('admin.pembelian.index') }}"
        class="px-5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-2">
        <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Batal
    </a>
    <button type="submit"
        class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition flex items-center gap-2 shadow-sm">
        <iconify-icon icon="ri:send-plane-line"></iconify-icon> Buat Purchase Order
    </button>
</div>

</form>
@endsection

@push('scripts')
<script>
const barangOptions = `@foreach($barang as $b)<option value="{{ $b->id }}" data-harga="{{ $b->harga_jual }}" data-satuan="{{ $b->satuan }}">{{ $b->kode }} - {{ $b->nama }}</option>@endforeach`;
function formatRp(n) { return 'Rp ' + Math.round(n).toLocaleString('id-ID'); }
function hitungSubtotal(row) {
    const qty = parseFloat(row.querySelector('.input-jumlah').value) || 0;
    const priceStr = row.querySelector('.input-harga').value || '';
    const price = parseFloat(priceStr.replace(/\./g, '')) || 0;
    const sub = qty * price;
    row.querySelector('.subtotal-label').textContent = formatRp(sub);
    hitungTotal();
}
function hitungTotal() {
    let t = 0;
    document.querySelectorAll('.subtotal-label').forEach(el => t += parseFloat(el.textContent.replace(/[^0-9]/g,''))||0);
    document.getElementById('grand-total').textContent = formatRp(t);
}
function bindRow(row) {
    row.querySelector('.select-barang').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        let hrg = opt.dataset.harga || 0;
        row.querySelector('.input-harga').value = window.formatRibuan ? window.formatRibuan(hrg) : hrg;
        row.querySelector('.satuan-label').textContent = opt.dataset.satuan||'';
        hitungSubtotal(row);
    });
    row.querySelector('.input-jumlah').addEventListener('input', () => hitungSubtotal(row));
    row.querySelector('.input-harga').addEventListener('input', () => hitungSubtotal(row));
    row.querySelector('.btn-remove-row').addEventListener('click', function() {
        if (document.querySelectorAll('.row-barang').length > 1) { row.remove(); hitungTotal(); }
    });
}
document.querySelectorAll('.row-barang').forEach(bindRow);
document.getElementById('btn-add-row').addEventListener('click', function() {
    const tbody = document.getElementById('tbody-barang');
    const newRow = document.createElement('tr');
    newRow.className = 'row-barang';
    newRow.innerHTML = `
        <td class="px-5 py-3"><select name="barang_id[]" class="select-barang w-full px-3 py-2 rounded-lg border border-neutral-300 text-sm bg-white focus:outline-none" required><option value="">-- Pilih Barang --</option>${barangOptions}</select></td>
        <td class="px-5 py-3"><div class="flex items-center gap-2"><input type="number" name="jumlah[]" class="input-jumlah w-24 px-3 py-2 rounded-lg border border-neutral-300 text-sm bg-white focus:outline-none" min="1" value="1" required><span class="text-xs text-secondary-light satuan-label"></span></div></td>
        <td class="px-5 py-3"><input type="text" name="harga_satuan[]" class="input-harga input-ribuan w-full px-3 py-2 rounded-lg border border-neutral-300 text-sm bg-white focus:outline-none" min="0" value="0" required></td>
        <td class="px-5 py-3 text-right font-bold text-sm subtotal-label">Rp 0</td>
        <td class="px-5 py-3 text-center"><button type="button" class="btn-remove-row w-8 h-8 rounded-lg bg-red-100 text-red-500 hover:bg-red-200 flex items-center justify-center transition"><iconify-icon icon="ri:delete-bin-line" class="text-sm"></iconify-icon></button></td>
    `;
    tbody.appendChild(newRow);
    bindRow(newRow);
});
</script>

<script>
// ===== Bukti Pembayaran Upload =====
const dropZone   = document.getElementById('drop-zone');
const inputBukti = document.getElementById('input-bukti');
const placeholder = document.getElementById('upload-placeholder');
const previewBox  = document.getElementById('upload-preview');
const previewImg  = document.getElementById('preview-img');
const previewPdf  = document.getElementById('preview-pdf');
const previewName = document.getElementById('preview-name');
const previewSize = document.getElementById('preview-size');
const btnRemove   = document.getElementById('btn-remove-file');

function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function showPreview(file) {
    previewName.textContent = file.name;
    previewSize.textContent = formatBytes(file.size);
    placeholder.classList.add('hidden');
    previewBox.classList.remove('hidden');
    dropZone.classList.remove('text-center', 'py-8');
    dropZone.classList.add('py-4');
    if (file.type === 'application/pdf') {
        document.getElementById('preview-img-wrap').classList.add('hidden');
        previewPdf.classList.remove('hidden');
    } else {
        previewPdf.classList.add('hidden');
        document.getElementById('preview-img-wrap').classList.remove('hidden');
        const reader = new FileReader();
        reader.onload = e => previewImg.src = e.target.result;
        reader.readAsDataURL(file);
    }
    dropZone.classList.add('border-warning-400', 'bg-warning-50');
    dropZone.classList.remove('border-dashed', 'border-neutral-300');
}

function resetUpload() {
    inputBukti.value = '';
    previewImg.src = '';
    previewBox.classList.add('hidden');
    placeholder.classList.remove('hidden');
    dropZone.classList.remove('border-warning-400', 'bg-warning-50', 'py-4');
    dropZone.classList.add('border-dashed', 'border-neutral-300', 'py-8', 'text-center');
}

// Click to open
dropZone.addEventListener('click', (e) => {
    if (e.target !== btnRemove && !btnRemove.contains(e.target)) inputBukti.click();
});

// File selected
inputBukti.addEventListener('change', () => {
    if (inputBukti.files[0]) showPreview(inputBukti.files[0]);
});

// Remove
btnRemove.addEventListener('click', (e) => { e.stopPropagation(); resetUpload(); });

// Drag & drop
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-warning-400', 'bg-warning-50'); });
dropZone.addEventListener('dragleave', () => { if (!inputBukti.files[0]) dropZone.classList.remove('border-warning-400', 'bg-warning-50'); });
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        inputBukti.files = dt.files;
        showPreview(file);
    }
});
</script>
@endpush
