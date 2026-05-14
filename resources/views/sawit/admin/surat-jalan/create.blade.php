@extends('layouts.sawit')
@section('title', 'Buat Surat Jalan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div><h6 class="font-semibold mb-0 dark:text-white">Buat Surat Jalan</h6></div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('sawit.admin.dashboard') }}" class="hover:text-primary-600">Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="font-medium"><a href="{{ route('sawit.admin.surat-jalan.index') }}" class="hover:text-primary-600">Surat Jalan</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Buat</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Form Surat Jalan</h6>
    </div>
    <div class="p-6">
        <form action="{{ route('sawit.admin.surat-jalan.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">No Surat Jalan <span class="text-red-500">*</span></label>
                    <input type="text" name="no_surat_jalan" value="{{ old('no_surat_jalan', $noSuratJalan) }}" required readonly class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-neutral-50 dark:bg-neutral-800 dark:text-white focus:outline-none">
                    @error('no_surat_jalan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('tanggal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Pilih Penjualan <span class="text-red-500">*</span></label>
                    <select name="penjualan_id" id="penjualan_id" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                        <option value="">-- Pilih Invoice Penjualan --</option>
                        @foreach($penjualan as $p)
                        <option value="{{ $p->id }}"
                            data-perusahaan="{{ $p->perusahaan->nama_perusahaan }}"
                            data-alamat="{{ $p->perusahaan->alamat }}"
                            data-jenis="{{ $p->barang->nama_sawit }}"
                            data-kg="{{ $p->total_kg_setelah_refaksi }}"
                            data-kendaraan="{{ $p->jenis_kendaraan }}"
                            data-mobil="{{ $p->no_mobil }}"
                            data-supir="{{ $p->nama_supir }}"
                            {{ old('penjualan_id', $selectedPenjualan?->id)==$p->id?'selected':'' }}>
                            {{ $p->no_invoice }} - {{ $p->perusahaan->nama_perusahaan }} ({{ number_format($p->total_kg_setelah_refaksi, 2) }} Kg)
                        </option>
                        @endforeach
                    </select>
                    @error('penjualan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Perusahaan / Tujuan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $selectedPenjualan?->perusahaan->nama_perusahaan) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('nama_perusahaan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Alamat Tujuan <span class="text-red-500">*</span></label>
                    <input type="text" name="alamat_tujuan" id="alamat_tujuan" value="{{ old('alamat_tujuan', $selectedPenjualan?->perusahaan->alamat) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('alamat_tujuan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Jenis Sawit <span class="text-red-500">*</span></label>
                    <input type="text" name="jenis_sawit" id="jenis_sawit" value="{{ old('jenis_sawit', $selectedPenjualan?->barang->nama_sawit) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('jenis_sawit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Total Kg <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="total_kg" id="total_kg" value="{{ old('total_kg', $selectedPenjualan?->total_kg_setelah_refaksi) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('total_kg')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Jenis Kendaraan <span class="text-red-500">*</span></label>
                    <input type="text" name="jenis_kendaraan" id="jenis_kendaraan" value="{{ old('jenis_kendaraan', $selectedPenjualan?->jenis_kendaraan) }}" required placeholder="Truk, Pick Up, dll" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('jenis_kendaraan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">No Mobil / Plat <span class="text-red-500">*</span></label>
                    <input type="text" name="no_mobil" id="no_mobil" value="{{ old('no_mobil', $selectedPenjualan?->no_mobil) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('no_mobil')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Supir <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_supir" id="nama_supir" value="{{ old('nama_supir', $selectedPenjualan?->nama_supir) }}" required class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500">
                    @error('nama_supir')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="mt-5">
                <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="2" placeholder="Opsional" class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-success-500 resize-none">{{ old('keterangan') }}</textarea>
            </div>
            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-neutral-200 dark:border-neutral-600">
                <a href="{{ route('sawit.admin.surat-jalan.index') }}" class="px-5 py-2.5 rounded-lg border border-neutral-300 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition flex items-center gap-2"><iconify-icon icon="ri:arrow-left-line"></iconify-icon> Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition flex items-center gap-2"><iconify-icon icon="ri:save-line"></iconify-icon> Simpan Surat Jalan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('penjualan_id');
    select.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        if (this.value) {
            document.getElementById('nama_perusahaan').value = opt.dataset.perusahaan || '';
            document.getElementById('alamat_tujuan').value = opt.dataset.alamat || '';
            document.getElementById('jenis_sawit').value = opt.dataset.jenis || '';
            document.getElementById('total_kg').value = opt.dataset.kg || '';
            document.getElementById('jenis_kendaraan').value = opt.dataset.kendaraan || '';
            document.getElementById('no_mobil').value = opt.dataset.mobil || '';
            document.getElementById('nama_supir').value = opt.dataset.supir || '';
        }
    });
});
</script>
@endpush
@endsection
