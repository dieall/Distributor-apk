@extends('layouts.sawit')
@section('title', 'Tambah Penjualan Sawit')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Tambah Penjualan Sawit</h6>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('sawit.admin.dashboard') }}" class="hover:text-primary-600">Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="font-medium"><a href="{{ route('sawit.admin.penjualan.index') }}" class="hover:text-primary-600">Penjualan</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Tambah</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Form Penjualan Sawit</h6>
    </div>

    <div class="p-6">
        <form action="{{ route('sawit.admin.penjualan.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- No Invoice --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">No Invoice <span class="text-red-500">*</span></label>
                    <input type="text" name="no_invoice" value="{{ old('no_invoice', $noInvoice) }}" required readonly
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-neutral-50 dark:bg-neutral-800 dark:text-white focus:outline-none">
                    @error('no_invoice')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                        <option value="draft" {{ old('status')==='draft'?'selected':'' }}>Draft</option>
                        <option value="done" {{ old('status')==='done'?'selected':'' }}>Done</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Perusahaan --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Perusahaan <span class="text-red-500">*</span></label>
                    <select name="perusahaan_id" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                        <option value="">-- Pilih Perusahaan --</option>
                        @foreach($perusahaan as $p)
                        <option value="{{ $p->id }}" {{ old('perusahaan_id')==$p->id?'selected':'' }}>{{ $p->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                    @error('perusahaan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Jenis Sawit --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Jenis Sawit <span class="text-red-500">*</span></label>
                    <select name="barang_id" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}" {{ old('barang_id')==$b->id?'selected':'' }}>{{ $b->nama_sawit }}</option>
                        @endforeach
                    </select>
                    @error('barang_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- QTY Timbangan --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">QTY / Total Kg <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="qty_timbangan" value="{{ old('qty_timbangan') }}" required id="qty_timbangan" placeholder="0.00"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('qty_timbangan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Refaksi --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Pengurangan / Refaksi</label>
                    <input type="number" step="0.01" name="refaksi" value="{{ old('refaksi', 0) }}" id="refaksi" placeholder="0.00"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('refaksi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Total Kg Setelah Refaksi (display only) --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Total Kg Setelah Refaksi</label>
                    <div class="px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 bg-neutral-50 dark:bg-neutral-800">
                        <span id="display_total_kg" class="text-sm font-bold text-success-600">0.00 Kg</span>
                    </div>
                </div>

                {{-- Harga per Kg --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Harga per Kg <span class="text-red-500">*</span></label>
                    <input type="number" step="1" name="harga_per_kg" value="{{ old('harga_per_kg') }}" required id="harga_per_kg" placeholder="0"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('harga_per_kg')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Total Harga (display only) --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Total Harga</label>
                    <div class="px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 bg-neutral-50 dark:bg-neutral-800">
                        <span id="display_total_harga" class="text-sm font-bold text-primary-600">Rp 0</span>
                    </div>
                </div>

                {{-- Selisih (display only) --}}
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Selisih</label>
                    <div class="px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 bg-neutral-50 dark:bg-neutral-800">
                        <span id="display_selisih_timbangan" class="text-sm font-bold text-neutral-600">0.00 Kg</span>
                    </div>
                </div>
            </div>

            {{-- Pembayaran --}}
            <h6 class="font-semibold text-sm dark:text-white mt-8 mb-4 pt-5 border-t border-neutral-200 dark:border-neutral-600">Pembayaran Invoice</h6>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar') }}"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Pembayaran Invoice</label>
                    <input type="number" step="1" name="pembayaran_invoice" value="{{ old('pembayaran_invoice', 0) }}" id="pembayaran_invoice" placeholder="0"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Potongan</label>
                    <input type="number" step="1" name="potongan" value="{{ old('potongan', 0) }}" id="potongan" placeholder="0"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Selisih Pembayaran</label>
                    <div class="px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 bg-neutral-50 dark:bg-neutral-800">
                        <span id="display_selisih_bayar" class="text-sm font-bold text-danger-600">Rp 0</span>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Keterangan Potongan</label>
                    <input type="text" name="keterangan_potongan" value="{{ old('keterangan_potongan') }}" placeholder="Opsional"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
            </div>

            {{-- Kendaraan --}}
            <h6 class="font-semibold text-sm dark:text-white mt-8 mb-4 pt-5 border-t border-neutral-200 dark:border-neutral-600">Informasi Kendaraan</h6>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Jenis Kendaraan</label>
                    <input type="text" name="jenis_kendaraan" value="{{ old('jenis_kendaraan') }}" placeholder="Contoh: Truk, Pick Up"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">No Mobil / Plat</label>
                    <input type="text" name="no_mobil" value="{{ old('no_mobil') }}" placeholder="Contoh: B 1234 ABC"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Supir</label>
                    <input type="text" name="nama_supir" value="{{ old('nama_supir') }}" placeholder="Nama supir"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="mt-5">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="2" placeholder="Opsional" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500 resize-none">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-neutral-200 dark:border-neutral-600">
                <a href="{{ route('sawit.admin.penjualan.index') }}" class="px-5 py-2.5 rounded-lg border border-neutral-300 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition flex items-center gap-2"><iconify-icon icon="ri:arrow-left-line"></iconify-icon> Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition flex items-center gap-2"><iconify-icon icon="ri:save-line"></iconify-icon> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const qty = document.getElementById('qty_timbangan');
    const refaksi = document.getElementById('refaksi');
    const harga = document.getElementById('harga_per_kg');
    const bayar = document.getElementById('pembayaran_invoice');
    const potongan = document.getElementById('potongan');

    function calc() {
        const q = parseFloat(qty.value) || 0;
        const r = parseFloat(refaksi.value) || 0;
        const h = parseFloat(harga.value) || 0;
        const b = parseFloat(bayar.value) || 0;
        const p = parseFloat(potongan.value) || 0;

        const totalKg = q - r;
        const selisih = q - totalKg;
        const totalHarga = totalKg * h;
        const selisihBayar = totalHarga - b - p;

        document.getElementById('display_total_kg').textContent = totalKg.toFixed(2) + ' Kg';
        document.getElementById('display_selisih_timbangan').textContent = selisih.toFixed(2) + ' Kg';
        document.getElementById('display_total_harga').textContent = 'Rp ' + Math.round(totalHarga).toLocaleString('id-ID');
        document.getElementById('display_selisih_bayar').textContent = 'Rp ' + Math.round(selisihBayar).toLocaleString('id-ID');
    }

    [qty, refaksi, harga, bayar, potongan].forEach(el => el.addEventListener('input', calc));
    calc();
});
</script>
@endpush
@endsection
