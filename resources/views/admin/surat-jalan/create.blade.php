@extends('layouts.app')
@section('title', 'Buat Surat Jalan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Buat Surat Jalan</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Buat dokumen pengiriman untuk permintaan yang sudah diproses</p>
    </div>
    <a href="{{ route('admin.surat-jalan.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm font-medium text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 flex items-center gap-2">
        <iconify-icon icon="ri:arrow-left-line"></iconify-icon> Kembali
    </a>
</div>

@include('partials.alert')

<form action="{{ route('admin.surat-jalan.store') }}" method="POST" id="form-sj">
@csrf

<div class="w-full space-y-6">
    {{-- Pilih permintaan --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
            <iconify-icon icon="ri:file-list-3-line" class="text-primary-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Pilih Permintaan</h6>
        </div>
        <div class="p-6">
            <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Permintaan Barang <span class="text-red-500">*</span></label>
            <select name="permintaan_id" id="sel-permintaan" required onchange="this.form.submit()"
                class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">-- Pilih permintaan yang sudah diproses --</option>
                @foreach($permintaan as $p)
                <option value="{{ $p->id }}" {{ ($selected && $selected->id == $p->id) ? 'selected' : '' }}>
                    {{ $p->no_permintaan }} — {{ $p->pelanggan->name }} ({{ $p->tanggal_request->format('d/m/Y') }})
                </option>
                @endforeach
            </select>
            @if($permintaan->isEmpty())
            <p class="text-warning-600 text-xs mt-2">Tidak ada permintaan yang siap dibuat surat jalan. Permintaan harus berstatus <strong>Diproses</strong> dan belum memiliki surat jalan.</p>
            @endif
        </div>
    </div>

    {{-- Detail Pengiriman — lebar penuh --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
            <iconify-icon icon="ri:truck-line" class="text-primary-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Detail Pengiriman</h6>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Tanggal Pengiriman <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Nama Driver</label>
                    <input type="text" name="driver" value="{{ old('driver') }}" placeholder="Nama pengemudi"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">No. Kendaraan</label>
                    <input type="text" name="no_kendaraan" value="{{ old('no_kendaraan') }}" placeholder="Contoh: B 1234 ABC"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div class="md:col-span-2 xl:col-span-3">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Alamat Tujuan</label>
                    <textarea name="alamat_tujuan" rows="2" placeholder="Alamat lengkap pengiriman"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">{{ old('alamat_tujuan', $selected ? ($selected->pelanggan->address ?? '') : '') }}</textarea>
                </div>
                <div class="md:col-span-2 xl:col-span-3">
                    <label class="block text-sm font-semibold text-neutral-700 dark:text-neutral-200 mb-1.5">Catatan</label>
                    <textarea name="catatan" rows="2" placeholder="Keterangan tambahan"
                        class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none">{{ old('catatan') }}</textarea>
                </div>
            </div>
            <button type="submit" {{ $selected ? '' : 'disabled' }}
                class="mt-6 w-full sm:w-auto px-8 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-medium inline-flex items-center justify-center gap-2 transition shadow-sm">
                <iconify-icon icon="ri:file-text-line"></iconify-icon> Buat Surat Jalan
            </button>
        </div>
    </div>

    {{-- Preview barang — lebar penuh --}}
    @if($selected)
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex items-center gap-2">
            <iconify-icon icon="ri:box-3-line" class="text-success-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Barang yang Akan Dikirim</h6>
        </div>
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-0 text-sm">
                <thead>
                    <tr class="bg-neutral-50 dark:bg-neutral-800">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Barang</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Disetujui</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Harga</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-secondary-light uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                    @php $grandTotal = 0; @endphp
                    @foreach($selected->detail->where('is_checked', true) as $d)
                    @php $grandTotal += $d->subtotal_jual; @endphp
                    <tr>
                        <td class="px-5 py-3">
                            <p class="font-semibold text-sm dark:text-white mb-0">{{ $d->barang->nama }}</p>
                            <p class="text-xs text-secondary-light mb-0">{{ $d->barang->kode }}</p>
                        </td>
                        <td class="px-5 py-3 text-right text-sm dark:text-white whitespace-nowrap">{{ number_format($d->jumlah_disetujui,0,',','.') }} {{ $d->barang->satuan }}</td>
                        <td class="px-5 py-3 text-right text-sm dark:text-white whitespace-nowrap">Rp {{ number_format($d->harga_jual,0,',','.') }}</td>
                        <td class="px-5 py-3 text-right font-bold text-success-600 whitespace-nowrap">Rp {{ number_format($d->subtotal_jual,0,',','.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-neutral-50 dark:bg-neutral-800">
                        <td colspan="3" class="px-5 py-3 text-right text-sm font-bold dark:text-white">Total</td>
                        <td class="px-5 py-3 text-right font-bold text-success-600 text-base whitespace-nowrap">Rp {{ number_format($grandTotal,0,',','.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif
</div>
</form>
@endsection
