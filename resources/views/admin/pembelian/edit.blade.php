@extends('layouts.app')
@section('title', 'Edit Purchase Order')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Edit Purchase Order</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Edit PO pembelian barang ke supplier</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ fin_route('pembelian.index') }}" class="hover:text-primary-600">Purchase Order</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Edit PO</li>
    </ul>
</div>

@include('partials.alert')

<form action="{{ fin_route('pembelian.update', $pembelian) }}" method="POST" id="form-po" enctype="multipart/form-data">
@csrf
@method('PUT')

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
                    <option value="{{ $sup->id }}" {{ old('supplier_id', $pembelian->supplier_id) == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal PO <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal" value="{{ old('tanggal', $pembelian->tanggal->format('Y-m-d')) }}" required
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 border-neutral-300 dark:border-neutral-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Estimasi Tanggal Terima</label>
                <input type="date" name="tanggal_kirim_estimasi" value="{{ old('tanggal_kirim_estimasi', $pembelian->tanggal_kirim_estimasi?->format('Y-m-d')) }}"
                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 border-neutral-300 dark:border-neutral-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Catatan</label>
                <textarea name="catatan" rows="1" placeholder="Catatan pembelian (opsional)"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">{{ old('catatan', $pembelian->catatan) }}</textarea>
            </div>
            @if(auth()->user()->isAdmin())
            <div>
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Status PO</label>
                <select name="status"
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                    @foreach(['draft','dikirim','sebagian_diterima','diterima','dibatalkan'] as $statusOption)
                    <option value="{{ $statusOption }}" {{ old('status', $pembelian->status) === $statusOption ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $statusOption)) }}
                    </option>
                    @endforeach
                </select>
                <p class="text-neutral-400 text-xs mt-1">Khusus admin: status bisa diubah langsung dari form edit.</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Bukti Pembayaran --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-200 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
        <iconify-icon icon="ri:image-line" class="text-warning-600 text-lg"></iconify-icon>
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Bukti Pembayaran {{ !empty($pembelian->bukti_pembayaran) ? '('.count($pembelian->bukti_pembayaran).' File)' : '' }}</h6>
        <span class="ml-auto text-xs text-secondary-light">Opsional — Biarkan kosong jika tidak diubah. Upload baru akan menggantikan file lama.</span>
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
            <div id="upload-preview" class="hidden text-left flex flex-wrap gap-3 mt-4">
                <!-- Preview items will be injected here -->
            </div>

            <input type="file" name="bukti_pembayaran[]" id="input-bukti" accept="image/*,.pdf" class="hidden" multiple>
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
                @foreach($pembelian->detail as $det)
                <tr class="row-barang">
                    <td class="px-5 py-3">
                        <select name="barang_id[]" class="select-barang w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang as $b)
                            <option value="{{ $b->id }}" data-harga="{{ $b->harga_jual }}" data-satuan="{{ $b->satuan }}" {{ $det->barang_id == $b->id ? 'selected' : '' }}>{{ $b->kode }} - {{ $b->nama }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <input type="number" name="jumlah[]" class="input-jumlah w-24 px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none" min="1" value="{{ $det->jumlah }}" required>
                            <span class="text-xs text-secondary-light satuan-label">{{ $det->barang->satuan ?? '' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <input type="text" name="harga_satuan[]" class="input-harga input-ribuan w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none" min="0" value="{{ $det->harga_satuan }}" required>
                    </td>
                    <td class="px-5 py-3 text-right font-bold text-sm dark:text-white subtotal-label">Rp {{ number_format($det->subtotal, 0, ',', '.') }}</td>
                    <td class="px-5 py-3 text-center">
                        <button type="button" class="btn-remove-row w-8 h-8 rounded-lg bg-red-100 text-red-500 hover:bg-red-200 flex items-center justify-center transition">
                            <iconify-icon icon="ri:delete-bin-line" class="text-sm"></iconify-icon>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <td colspan="3" class="px-5 py-3 text-right font-bold text-sm dark:text-white">Total</td>
                    <td class="px-5 py-3 text-right font-bold text-primary-600 text-base" id="grand-total">Rp {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="flex items-center justify-end gap-3">
    <a href="{{ fin_route('pembelian.index') }}"
        class="px-5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 transition flex items-center gap-2">
        <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Batal
    </a>
    <button type="submit"
        class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition flex items-center gap-2 shadow-sm">
        <iconify-icon icon="ri:save-line"></iconify-icon> Simpan Perubahan
    </button>
</div>

</form>
@endsection

@push('scripts')
<script>
const barangOptions = `@foreach($barang as $b)<option value="{{ $b->id }}" data-harga="{{ $b->harga_jual }}" data-satuan="{{ $b->satuan }}">{{ $b->kode }} - {{ $b->nama }}</option>@endforeach`;
function formatRp(n) { return 'Rp ' + Math.round(n).toLocaleString('id-ID'); }
function parseAngka(value) {
    let raw = String(value ?? '').trim();
    if (!raw) return 0;

    // Sisakan hanya digit + pemisah desimal/ribuan umum.
    raw = raw.replace(/[^\d.,-]/g, '');

    const dotCount = (raw.match(/\./g) || []).length;
    const commaCount = (raw.match(/,/g) || []).length;

    // Format campuran Indonesia: 1.234.567,89
    if (dotCount > 0 && commaCount > 0) {
        return parseFloat(raw.replace(/\./g, '').replace(',', '.')) || 0;
    }

    // Hanya koma: bisa desimal (10,5) atau ribuan (1,234)
    if (commaCount > 0) {
        const parts = raw.split(',');
        if (parts.length === 2 && parts[1].length === 3) {
            return parseFloat(parts.join('')) || 0;
        }
        return parseFloat(raw.replace(',', '.')) || 0;
    }

    // Hanya titik: bisa desimal DB (1700000.00) atau ribuan (1.700.000)
    if (dotCount > 0) {
        const parts = raw.split('.');
        if (parts.length > 2) {
            return parseFloat(parts.join('')) || 0;
        }
        if (parts.length === 2 && parts[1].length === 3) {
            return parseFloat(parts.join('')) || 0;
        }
        return parseFloat(raw) || 0;
    }

    return parseFloat(raw) || 0;
}
function hitungSubtotal(row) {
    const qty = parseFloat(row.querySelector('.input-jumlah').value) || 0;
    const price = Math.round(parseAngka(row.querySelector('.input-harga').value));
    const sub = qty * price;
    row.querySelector('.subtotal-label').textContent = formatRp(sub);
    return sub;
}
function hitungTotal() {
    let t = 0;
    document.querySelectorAll('.row-barang').forEach(row => {
        t += hitungSubtotal(row);
    });
    document.getElementById('grand-total').textContent = formatRp(t);
}
function bindRow(row) {
    row.querySelector('.select-barang').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        let hrg = opt.dataset.harga || 0;
        row.querySelector('.input-harga').value = window.formatRibuan ? window.formatRibuan(hrg) : hrg;
        row.querySelector('.satuan-label').textContent = opt.dataset.satuan||'';
        hitungTotal();
    });
    row.querySelector('.input-jumlah').addEventListener('input', () => hitungTotal());
    row.querySelector('.input-harga').addEventListener('input', () => hitungTotal());
    row.querySelector('.input-harga').addEventListener('blur', function() {
        const price = Math.round(parseAngka(this.value));
        this.value = window.formatRibuan ? window.formatRibuan(price) : price;
        hitungTotal();
    });
    row.querySelector('.btn-remove-row').addEventListener('click', function() {
        if (document.querySelectorAll('.row-barang').length > 1) { row.remove(); hitungTotal(); }
    });
}
document.querySelectorAll('.row-barang').forEach(bindRow);
document.querySelectorAll('.row-barang .input-harga').forEach((input) => {
    const price = Math.round(parseAngka(input.value));
    input.value = window.formatRibuan ? window.formatRibuan(price) : price;
});
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
hitungTotal();
</script>

<script>
// ===== Bukti Pembayaran Upload (Multiple) =====
const dropZone    = document.getElementById('drop-zone');
const inputBukti  = document.getElementById('input-bukti');
const placeholder = document.getElementById('upload-placeholder');
const previewBox  = document.getElementById('upload-preview');

let selectedFiles = [];

function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function updateInputFiles() {
    const dt = new DataTransfer();
    selectedFiles.forEach(file => dt.items.add(file));
    inputBukti.files = dt.files;
}

function renderPreviews() {
    previewBox.innerHTML = '';
    
    if (selectedFiles.length === 0) {
        previewBox.classList.add('hidden');
        placeholder.classList.remove('hidden');
        dropZone.classList.remove('border-warning-400', 'bg-warning-50', 'py-4');
        dropZone.classList.add('border-dashed', 'border-neutral-300', 'py-8', 'text-center');
        return;
    }

    placeholder.classList.add('hidden');
    previewBox.classList.remove('hidden');
    dropZone.classList.remove('text-center', 'py-8');
    dropZone.classList.add('py-4', 'border-warning-400', 'bg-warning-50');
    dropZone.classList.remove('border-dashed', 'border-neutral-300');

    selectedFiles.forEach((file, index) => {
        const div = document.createElement('div');
        div.className = 'bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-600 rounded-xl p-3 inline-flex items-center gap-3 w-full sm:w-[calc(50%-0.375rem)] lg:w-[calc(33.333%-0.5rem)]';
        
        let mediaHtml = '';
        if (file.type === 'application/pdf') {
            mediaHtml = `<div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                            <iconify-icon icon="ri:file-pdf-line" class="text-red-500 text-2xl"></iconify-icon>
                         </div>`;
        } else {
            mediaHtml = `<div class="w-12 h-12 rounded-lg overflow-hidden border border-neutral-200 flex-shrink-0 bg-neutral-100 flex items-center justify-center">
                            <img src="${URL.createObjectURL(file)}" alt="Preview" class="max-w-full max-h-full object-cover">
                         </div>`;
        }

        div.innerHTML = `
            ${mediaHtml}
            <div class="flex-1 min-w-0 pr-2">
                <p class="text-xs font-semibold text-neutral-800 dark:text-white mb-0.5 truncate">${file.name}</p>
                <p class="text-[10px] text-secondary-light mb-0">${formatBytes(file.size)}</p>
            </div>
            <button type="button" class="btn-remove-file w-7 h-7 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center flex-shrink-0 transition-colors" data-index="${index}">
                <iconify-icon icon="ri:close-line" class="text-base"></iconify-icon>
            </button>
        `;
        previewBox.appendChild(div);
    });

    document.querySelectorAll('.btn-remove-file').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const idx = parseInt(btn.getAttribute('data-index'));
            selectedFiles.splice(idx, 1);
            updateInputFiles();
            renderPreviews();
        });
    });
}

// Click to open
dropZone.addEventListener('click', (e) => {
    if (!e.target.closest('.btn-remove-file')) inputBukti.click();
});

// File selected
inputBukti.addEventListener('change', () => {
    if (inputBukti.files.length > 0) {
        // Append new files up to max 5
        Array.from(inputBukti.files).forEach(file => {
            if (selectedFiles.length < 5) selectedFiles.push(file);
        });
        updateInputFiles();
        renderPreviews();
    }
});

// Drag & drop
dropZone.addEventListener('dragover', e => { 
    e.preventDefault(); 
    dropZone.classList.add('border-warning-400', 'bg-warning-50'); 
});
dropZone.addEventListener('dragleave', () => { 
    if (selectedFiles.length === 0) dropZone.classList.remove('border-warning-400', 'bg-warning-50'); 
});
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    if (e.dataTransfer.files.length > 0) {
        Array.from(e.dataTransfer.files).forEach(file => {
            if (selectedFiles.length < 5) selectedFiles.push(file);
        });
        updateInputFiles();
        renderPreviews();
    }
});
</script>
@endpush
