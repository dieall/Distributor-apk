@extends('layouts.app')
@section('title', 'Tambah Pengeluaran')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Tambah Pengeluaran</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Input biaya harian/operasional. Untuk <strong>Aset</strong>, nilai dibagi per bulan (penyusutan/cicilan).</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ fin_route('pengeluaran.aset.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100">Daftar penyusutan aset</a>
        <a href="{{ fin_route('pengeluaran.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100">Kembali</a>
    </div>
</div>

@include('partials.alert')

<form action="{{ fin_route('pengeluaran.store') }}" method="POST" enctype="multipart/form-data" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-6">
    @csrf
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
        <div>
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal" id="input_tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <p class="text-xs text-secondary-light mt-1 mb-0" id="hint_tanggal_biasa">Tanggal transaksi.</p>
            <p class="text-xs text-secondary-light mt-1 mb-0 hidden" id="hint_tanggal_aset">Tanggal cicilan pertama (bulan ke-1) masuk ke jadwal.</p>
            @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Kategori <span class="text-red-500">*</span></label>
            <select name="kategori" id="select_kategori" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
                <option value="Operasional" {{ old('kategori', 'Operasional') == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                <option value="Transport / Uang Jajan" {{ old('kategori') == 'Transport / Uang Jajan' ? 'selected' : '' }}>Transport / Uang Jajan</option>
                <option value="Gaji" {{ old('kategori') == 'Gaji' ? 'selected' : '' }}>Gaji</option>
                <option value="Aset" {{ old('kategori') == 'Aset' ? 'selected' : '' }}>Aset</option>
            </select>
            @error('kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Keterangan <span class="text-red-500">*</span></label>
            <input type="text" name="keterangan" value="{{ old('keterangan') }}" placeholder="Contoh: BBM, freezer 2 pintu, dll." required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            @error('keterangan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div id="wrap_nominal_biasa" class="sm:col-span-2">
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nominal <span class="text-red-500">*</span></label>
            <input type="text" name="nominal" id="input_nominal" value="{{ old('nominal') }}" class="input-ribuan w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white" placeholder="0">
            @error('nominal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div id="wrap_aset" class="sm:col-span-2 hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Total nilai aset <span class="text-red-500">*</span></label>
                    <input type="text" name="total_nilai" id="input_total_aset" value="{{ old('total_nilai') }}" class="input-ribuan w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white" placeholder="0">
                    <p class="text-xs text-secondary-light mt-1 mb-0">Contoh: Rp 12.000.000 untuk freezer.</p>
                    @error('total_nilai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Jumlah bulan penyusutan <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_bulan" id="input_jumlah_bulan" value="{{ old('jumlah_bulan', 12) }}" min="2" max="120" step="1" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
                    <p class="text-xs text-secondary-light mt-1 mb-0">Nominal per bulan = total ÷ bulan (sisa pembulatan ke bulan terakhir).</p>
                    @error('jumlah_bulan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2 rounded-lg border border-primary-200 bg-primary-50/80 dark:bg-neutral-800 dark:border-primary-900 p-4 text-sm text-neutral-700 dark:text-neutral-200">
                    <p class="mb-0">Setelah simpan, jadwal cicilan dibuat. Setiap bulan yang <strong>dicentang</strong> di halaman edit akan membuat baris pengeluaran otomatis saat tanggalnya sudah lewat (cek harian jam 06:00 atau langsung setelah simpan/edit).</p>
                </div>
            </div>
        </div>

        <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Foto bukti <span class="text-neutral-400 font-normal">(opsional)</span></label>
            <input type="file" name="bukti_foto" id="bukti_foto" accept="image/jpeg,image/png,image/webp"
                class="w-full px-3 py-2 rounded-lg border border-dashed border-neutral-300 dark:border-neutral-500 text-sm bg-neutral-50 dark:bg-neutral-800 dark:text-white file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-600 file:text-white hover:file:bg-primary-700 cursor-pointer">
            <p class="text-xs text-secondary-light mt-1 mb-0">Format JPG, PNG, atau WebP &mdash; maks. 5 MB.</p>
            @error('bukti_foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            <div id="preview-wrap" class="hidden mt-3">
                <p class="text-xs font-medium text-neutral-600 dark:text-neutral-300 mb-1">Pratinjau</p>
                <img id="preview-img" src="" alt="" class="max-h-40 rounded-lg border border-neutral-200 shadow-sm object-contain">
            </div>
        </div>
    </div>
    <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium flex items-center gap-2">
        <iconify-icon icon="ri:save-line"></iconify-icon> Simpan
    </button>
</form>

<script>
(function () {
    const sel = document.getElementById('select_kategori');
    const wrapBiasa = document.getElementById('wrap_nominal_biasa');
    const wrapAset = document.getElementById('wrap_aset');
    const inputNominal = document.getElementById('input_nominal');
    const inputTotal = document.getElementById('input_total_aset');
    const inputBulan = document.getElementById('input_jumlah_bulan');
    const hintBiasa = document.getElementById('hint_tanggal_biasa');
    const hintAset = document.getElementById('hint_tanggal_aset');

    function syncMode() {
        const isAset = sel && sel.value === 'Aset';
        if (!wrapBiasa || !wrapAset) return;
        if (isAset) {
            wrapBiasa.classList.add('hidden');
            wrapAset.classList.remove('hidden');
            inputNominal.removeAttribute('required');
            inputNominal.setAttribute('disabled', 'disabled');
            inputTotal.setAttribute('required', 'required');
            inputBulan.setAttribute('required', 'required');
            hintBiasa.classList.add('hidden');
            hintAset.classList.remove('hidden');
        } else {
            wrapBiasa.classList.remove('hidden');
            wrapAset.classList.add('hidden');
            inputNominal.setAttribute('required', 'required');
            inputNominal.removeAttribute('disabled');
            inputTotal.removeAttribute('required');
            inputBulan.removeAttribute('required');
            hintBiasa.classList.remove('hidden');
            hintAset.classList.add('hidden');
        }
    }

    sel?.addEventListener('change', syncMode);
    syncMode();
})();

document.getElementById('bukti_foto')?.addEventListener('change', function () {
    const wrap = document.getElementById('preview-wrap');
    const img = document.getElementById('preview-img');
    const f = this.files?.[0];
    if (!f || !wrap || !img) return;
    wrap.classList.remove('hidden');
    img.src = URL.createObjectURL(f);
});
</script>
@endsection
