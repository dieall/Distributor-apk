@extends('layouts.sawit')
@section('title', 'Tambah Pembelian Sawit')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div><h6 class="font-semibold mb-0 dark:text-white">Tambah Pembelian Sawit</h6></div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('sawit.admin.dashboard') }}" class="hover:text-primary-600">Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="font-medium"><a href="{{ route('sawit.admin.pembelian.index') }}" class="hover:text-primary-600">Pembelian</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Tambah</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Form Pembelian Sawit</h6>
    </div>
    <div class="p-6">
        <form action="{{ route('sawit.admin.pembelian.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">No Pembelian <span class="text-red-500">*</span></label>
                    <input type="text" name="no_pembelian" value="{{ old('no_pembelian', $noPembelian) }}" required readonly class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-neutral-50 dark:bg-neutral-800 dark:text-white focus:outline-none">
                    @error('no_pembelian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                        <option value="draft" {{ old('status')==='draft'?'selected':'' }}>Draft</option>
                        <option value="done" {{ old('status')==='done'?'selected':'' }}>Done</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Jenis Sawit <span class="text-red-500">*</span></label>
                    <select name="barang_id" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}" {{ old('barang_id')==$b->id?'selected':'' }}>{{ $b->nama_sawit }}</option>
                        @endforeach
                    </select>
                    @error('barang_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Pembeli / Penjual <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_penjual" value="{{ old('nama_penjual') }}" required placeholder="Ketik nama pembeli/penjual" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('nama_penjual')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Type Payment <span class="text-red-500">*</span></label>
                    <select name="type_payment" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                        <option value="cash" {{ old('type_payment')==='cash'?'selected':'' }}>Cash</option>
                        <option value="transfer" {{ old('type_payment')==='transfer'?'selected':'' }}>Transfer</option>
                        <option value="tempo" {{ old('type_payment')==='tempo'?'selected':'' }}>Tempo</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">QTY Timbangan <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="qty_timbangan" value="{{ old('qty_timbangan') }}" required id="qty_timbangan" placeholder="0.00" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('qty_timbangan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Refaksi</label>
                    <input type="number" step="0.01" name="refaksi" value="{{ old('refaksi', 0) }}" id="refaksi" placeholder="0.00" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">QTY Setelah Refaksi</label>
                    <div class="px-3.5 py-2.5 rounded-lg border border-neutral-300 bg-neutral-50 dark:bg-neutral-800"><span id="display_qty_setelah" class="text-sm font-bold text-success-600">0.00 Kg</span></div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Harga per Kg <span class="text-red-500">*</span></label>
                    <input type="number" step="1" name="harga_per_kg" value="{{ old('harga_per_kg') }}" required id="harga_per_kg" placeholder="0" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('harga_per_kg')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Total Harga</label>
                    <div class="px-3.5 py-2.5 rounded-lg border border-neutral-300 bg-neutral-50 dark:bg-neutral-800"><span id="display_total_harga" class="text-sm font-bold text-primary-600">Rp 0</span></div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Potongan DP</label>
                    <input type="number" step="1" name="potongan_dp" value="{{ old('potongan_dp', 0) }}" id="potongan_dp" placeholder="0" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">DP Sebelumnya</label>
                    <input type="number" step="1" name="dp_sebelumnya" value="{{ old('dp_sebelumnya', 0) }}" id="dp_sebelumnya" placeholder="0" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal Transfer</label>
                    <input type="date" name="tanggal_transfer" value="{{ old('tanggal_transfer') }}" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Selisih (Total - Potongan DP)</label>
                    <div class="px-3.5 py-2.5 rounded-lg border border-neutral-300 bg-neutral-50 dark:bg-neutral-800"><span id="display_selisih" class="text-sm font-bold text-primary-600">Rp 0</span></div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Sisa DP (Selisih - DP Sebelumnya)</label>
                    <div class="px-3.5 py-2.5 rounded-lg border border-neutral-300 bg-neutral-50 dark:bg-neutral-800"><span id="display_sisa_dp" class="text-sm font-bold text-danger-600">Rp 0</span></div>
                </div>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="2" placeholder="Opsional" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500 resize-none">{{ old('keterangan') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-neutral-200 dark:border-neutral-600">
                <a href="{{ route('sawit.admin.pembelian.index') }}" class="px-5 py-2.5 rounded-lg border border-neutral-300 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition flex items-center gap-2"><iconify-icon icon="ri:arrow-left-line"></iconify-icon> Batal</a>
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
    const dp = document.getElementById('potongan_dp');
    const dpSebelumnya = document.getElementById('dp_sebelumnya');
    function calc() {
        const q = parseFloat(qty.value)||0, r = parseFloat(refaksi.value)||0, h = parseFloat(harga.value)||0, d = parseFloat(dp.value)||0, ds = parseFloat(dpSebelumnya.value)||0;
        const qtySetelah = q - r;
        const totalHarga = qtySetelah * h;
        const selisih = totalHarga - d;
        const sisaDp = selisih - ds;
        document.getElementById('display_qty_setelah').textContent = qtySetelah.toFixed(2) + ' Kg';
        document.getElementById('display_total_harga').textContent = 'Rp ' + Math.round(totalHarga).toLocaleString('id-ID');
        document.getElementById('display_selisih').textContent = 'Rp ' + Math.round(selisih).toLocaleString('id-ID');
        document.getElementById('display_sisa_dp').textContent = 'Rp ' + Math.round(sisaDp).toLocaleString('id-ID');
    }
    [qty, refaksi, harga, dp, dpSebelumnya].forEach(el => el.addEventListener('input', calc));
    calc();
});
</script>
@endpush
@endsection
