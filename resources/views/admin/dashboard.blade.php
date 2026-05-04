@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

{{-- Header --}}
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white text-lg">Dashboard Administrator</h6>
        <p class="text-secondary-light text-sm mb-0">Ringkasan data & performa sistem per {{ now()->translatedFormat('d F Y') }}</p>
    </div>
    <ul class="flex items-center gap-[6px]">
        <li class="font-medium">
            <a href="{{ dashboard_home_route() }}" class="flex items-center gap-2 hover:text-primary-600">
                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                Dashboard
            </a>
        </li>
        <li>-</li>
        <li class="font-medium">Administrator</li>
    </ul>
</div>

{{-- ===== ROW 1: KPI Cards ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    @php
    $kpis = [
        ['label'=>'Total Barang',       'value'=>$stats['total_barang'],       'icon'=>'ri:box-3-line',         'bg'=>'bg-blue-100',    'color'=>'text-blue-600'],
        ['label'=>'PO Bulan Ini',       'value'=>$stats['po_bulan_ini'],       'icon'=>'ri:file-list-3-line',   'bg'=>'bg-indigo-100',  'color'=>'text-indigo-600'],
        ['label'=>'Permintaan Pending', 'value'=>$stats['permintaan_pending'], 'icon'=>'ri:time-line',          'bg'=>'bg-warning-100', 'color'=>'text-warning-600'],
        ['label'=>'Stok Rendah',        'value'=>$stats['stok_rendah'],        'icon'=>'ri:alert-line',         'bg'=>'bg-red-100',     'color'=>'text-red-600'],
    ];
    @endphp

    @foreach($kpis as $kpi)
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4">
        <div class="flex items-center justify-between gap-2 mb-3">
            <div class="w-10 h-10 {{ $kpi['bg'] }} {{ $kpi['color'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="{{ $kpi['icon'] }}" class="text-xl"></iconify-icon>
            </div>
        </div>
        <h4 class="font-bold text-2xl dark:text-white mb-0">{{ $kpi['value'] }}</h4>
        <p class="text-secondary-light text-xs mt-1 mb-0">{{ $kpi['label'] }}</p>
    </div>
    @endforeach

</div>

{{-- ===== ROW 2: Nilai KPI Wide Cards ===== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    {{-- Nilai Pembelian Bulan Ini --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:shopping-cart-2-line" class="text-2xl"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-secondary-light text-sm mb-1">Nilai Pembelian Bulan Ini</p>
                <h4 class="font-bold text-xl dark:text-white mb-0">Rp {{ number_format($stats['nilai_pembelian'],0,',','.') }}</h4>
                <p class="text-xs text-secondary-light mb-0">{{ $stats['po_bulan_ini'] }} PO · {{ now()->translatedFormat('F Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Nilai Penjualan Bulan Ini --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-success-100 text-success-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:money-dollar-circle-line" class="text-2xl"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-secondary-light text-sm mb-1">Nilai Penjualan Bulan Ini</p>
                <h4 class="font-bold text-xl dark:text-white mb-0">Rp {{ number_format($stats['nilai_penjualan'],0,',','.') }}</h4>
                <p class="text-xs text-secondary-light mb-0">Dari barang yang disetujui sales</p>
            </div>
        </div>
    </div>

    {{-- Nilai Pengeluaran Bulan Ini --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-red-100 text-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:wallet-3-line" class="text-2xl"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-secondary-light text-sm mb-1">Pengeluaran Bulan Ini</p>
                <h4 class="font-bold text-xl dark:text-white mb-0">Rp {{ number_format($stats['nilai_pengeluaran'],0,',','.') }}</h4>
                <p class="text-xs text-secondary-light mb-0">Biaya harian/operasional</p>
            </div>
        </div>
    </div>

    {{-- Nilai Keuntungan Bulan Ini --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 {{ $stats['nilai_keuntungan'] >= 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:line-chart-line" class="text-2xl"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-secondary-light text-sm mb-1">Keuntungan Bulan Ini</p>
                <h4 class="font-bold text-xl {{ $stats['nilai_keuntungan'] >= 0 ? 'text-success-600' : 'text-red-500' }} mb-0">Rp {{ number_format($stats['nilai_keuntungan'],0,',','.') }}</h4>
                <p class="text-xs text-secondary-light mb-0">Penjualan - pembelian - pengeluaran</p>
            </div>
        </div>
    </div>

    {{-- Nilai Stok --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-success-100 text-success-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <iconify-icon icon="ri:store-3-line" class="text-2xl"></iconify-icon>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-secondary-light text-sm mb-1">Estimasi Nilai Stok Saat Ini</p>
                <h4 class="font-bold text-xl dark:text-white mb-0">Rp {{ number_format($stats['nilai_stok'],0,',','.') }}</h4>
                <p class="text-xs text-secondary-light mb-0">{{ $stats['total_barang'] }} jenis barang terdaftar</p>
            </div>
        </div>
    </div>

</div>

{{-- ===== ROW 2B: Profit Chart ===== --}}
<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-2">
            <iconify-icon icon="ri:funds-line" class="text-success-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Grafik Keuntungan (6 Bulan)</h6>
        </div>
        <span class="text-xs text-secondary-light">Penjualan - Pembelian - Pengeluaran</span>
    </div>
    <div class="p-5">
        <div id="chart-profit"></div>
    </div>
</div>

{{-- ===== ROW 3: Charts ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- Chart: Nilai Pembelian 6 Bulan --}}
    <div class="xl:col-span-2 card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="ri:bar-chart-2-line" class="text-primary-600 text-lg"></iconify-icon>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Nilai Pembelian (6 Bulan)</h6>
            </div>
            <span class="text-xs text-secondary-light">Rp / Bulan</span>
        </div>
        <div class="p-5">
            <div id="chart-pembelian"></div>
        </div>
    </div>

    {{-- Chart: Status PO Donut --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex items-center gap-2">
            <iconify-icon icon="ri:pie-chart-2-line" class="text-indigo-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Status Purchase Order</h6>
        </div>
        <div class="p-5">
            <div id="chart-po-status"></div>
            {{-- Legend --}}
            @php
            $statusMap = [
                'draft'             => ['label'=>'Draft',              'color'=>'bg-neutral-400'],
                'dikirim'           => ['label'=>'Dikirim',            'color'=>'bg-blue-400'],
                'sebagian_diterima' => ['label'=>'Sbgn. Diterima',     'color'=>'bg-warning-400'],
                'diterima'          => ['label'=>'Diterima',           'color'=>'bg-success-400'],
                'dibatalkan'        => ['label'=>'Dibatalkan',         'color'=>'bg-red-400'],
            ];
            @endphp
            <div class="mt-4 grid grid-cols-2 gap-2">
                @foreach($statusMap as $key => $info)
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full {{ $info['color'] }} flex-shrink-0"></span>
                    <span class="text-xs text-secondary-light">{{ $info['label'] }}: <strong class="text-neutral-800 dark:text-white">{{ $poStatus[$key] ?? 0 }}</strong></span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- ===== ROW 4: Permintaan Chart + Stok Rendah ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">

    {{-- Chart: Permintaan per Bulan --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex items-center gap-2">
            <iconify-icon icon="ri:customer-service-2-line" class="text-warning-600 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Permintaan Barang (6 Bulan)</h6>
        </div>
        <div class="p-5">
            <div id="chart-permintaan"></div>
        </div>
    </div>

    {{-- Stok Rendah Alert --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="ri:alert-line" class="text-red-500 text-lg"></iconify-icon>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Peringatan Stok Rendah</h6>
            </div>
            <a href="{{ dash_route('admin.barang.index') }}" class="text-xs text-primary-600 hover:underline">Lihat semua</a>
        </div>
        @if($stokRendah->isEmpty())
        <div class="p-10 text-center">
            <iconify-icon icon="ri:checkbox-circle-line" class="text-success-500 text-4xl mb-2 block"></iconify-icon>
            <p class="text-sm text-secondary-light mb-0">Semua stok dalam kondisi aman</p>
        </div>
        @else
        <div class="divide-y divide-neutral-100 dark:divide-neutral-600">
            @foreach($stokRendah as $item)
            @php
            $persen = $item->stok_minimum > 0 ? min(100, ($item->jumlah / $item->stok_minimum) * 100) : 0;
            $barColor = $persen == 0 ? 'bg-red-500' : ($persen < 50 ? 'bg-warning-500' : 'bg-success-500');
            @endphp
            <div class="px-6 py-3.5">
                <div class="flex items-center justify-between mb-1.5">
                    <div>
                        <p class="font-semibold text-sm dark:text-white mb-0">{{ $item->nama }}</p>
                        <p class="text-xs text-secondary-light mb-0">{{ $item->kode }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-sm {{ $item->jumlah == 0 ? 'text-red-500' : 'text-warning-600' }} mb-0">
                            {{ format_qty_id($item->jumlah) }} {{ $item->satuan }}
                        </p>
                        <p class="text-xs text-secondary-light mb-0">min: {{ format_qty_id($item->stok_minimum) }}</p>
                    </div>
                </div>
                <div class="w-full bg-neutral-200 dark:bg-neutral-600 rounded-full h-1.5">
                    <div class="{{ $barColor }} h-1.5 rounded-full transition-all" style="width: {{ $persen }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- ===== ROW 5: Recent Activity + Top Barang ===== --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

    {{-- Recent PO --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="ri:file-list-3-line" class="text-primary-600 text-lg"></iconify-icon>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">PO Terbaru</h6>
            </div>
            <a href="{{ dash_route('admin.pembelian.index') }}" class="text-xs text-primary-600 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-neutral-100 dark:divide-neutral-600">
            @forelse($recentPO as $po)
            @php
            $statusColors = ['draft'=>'bg-neutral-100 text-neutral-600','dikirim'=>'bg-blue-100 text-blue-600','sebagian_diterima'=>'bg-warning-100 text-warning-600','diterima'=>'bg-success-100 text-success-600','dibatalkan'=>'bg-red-100 text-red-600'];
            $sc = $statusColors[$po->status] ?? 'bg-neutral-100 text-neutral-600';
            @endphp
            <div class="px-5 py-3 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="font-semibold text-xs dark:text-white mb-0 truncate">{{ $po->no_po }}</p>
                    <p class="text-xs text-secondary-light mb-0">{{ $po->supplier->name ?? '-' }} · {{ $po->tanggal->format('d/m/Y') }}</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc }} whitespace-nowrap flex-shrink-0">{{ ucfirst(str_replace('_',' ',$po->status)) }}</span>
            </div>
            @empty
            <div class="p-6 text-center text-secondary-light text-sm">Belum ada PO</div>
            @endforelse
        </div>
    </div>

    {{-- Recent Permintaan --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <iconify-icon icon="ri:shopping-bag-3-line" class="text-warning-600 text-lg"></iconify-icon>
                <h6 class="font-semibold mb-0 dark:text-white text-sm">Permintaan Terbaru</h6>
            </div>
            <span class="text-xs text-secondary-light">Terbaru</span>
        </div>
        <div class="divide-y divide-neutral-100 dark:divide-neutral-600">
            @forelse($recentPermintaan as $pr)
            @php
            $prColors = ['pending'=>'bg-warning-100 text-warning-700','diproses'=>'bg-blue-100 text-blue-700','siap_kirim'=>'bg-indigo-100 text-indigo-700','selesai'=>'bg-success-100 text-success-700','dibatalkan'=>'bg-red-100 text-red-700'];
            $pc = $prColors[$pr->status] ?? 'bg-neutral-100 text-neutral-700';
            @endphp
            <div class="px-5 py-3 flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="font-semibold text-xs dark:text-white mb-0 truncate">{{ $pr->no_permintaan }}</p>
                    <p class="text-xs text-secondary-light mb-0">{{ $pr->pelanggan->name ?? '-' }} · {{ $pr->tanggal_request->format('d/m/Y') }}</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $pc }} whitespace-nowrap flex-shrink-0">{{ ucfirst(str_replace('_',' ',$pr->status)) }}</span>
            </div>
            @empty
            <div class="p-6 text-center text-secondary-light text-sm">Belum ada permintaan</div>
            @endforelse
        </div>
    </div>

    {{-- Top Barang Diminati --}}
    <div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex items-center gap-2">
            <iconify-icon icon="ri:fire-line" class="text-red-500 text-lg"></iconify-icon>
            <h6 class="font-semibold mb-0 dark:text-white text-sm">Top Barang Diminati</h6>
        </div>
        @if($topBarang->isEmpty())
        <div class="p-6 text-center text-secondary-light text-sm">Belum ada data permintaan</div>
        @else
        <div class="divide-y divide-neutral-100 dark:divide-neutral-600">
            @foreach($topBarang as $i => $b)
            @php
            $rankColors = ['bg-warning-500','bg-neutral-400','bg-amber-700','bg-neutral-300','bg-neutral-200'];
            @endphp
            <div class="px-5 py-3 flex items-center gap-3">
                <span class="w-6 h-6 rounded-full {{ $rankColors[$i] ?? 'bg-neutral-200' }} text-white text-xs font-bold flex items-center justify-center flex-shrink-0">{{ $i+1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-xs dark:text-white mb-0 truncate">{{ $b->nama }}</p>
                    <p class="text-xs text-secondary-light mb-0">{{ $b->kode }}</p>
                </div>
                <span class="text-xs font-bold text-primary-600">{{ number_format($b->total_diminta, 0, ',', '.') }}x</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

@unless(auth()->user()->isPurchasing())
{{-- ===== ROW 6: Quick Access (disembunyikan untuk purchasing) ===== --}}
<h6 class="font-semibold mb-4 dark:text-white">Akses Cepat</h6>
<div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-6 2xl:grid-cols-9 gap-4">
    @php
    $quickLinks = [
        ['label'=>'Tambah Barang',    'sub'=>'Input produk baru',       'icon'=>'ri:box-3-line',             'bg'=>'bg-blue-100',    'color'=>'text-blue-600',    'href'=>dash_route('admin.barang.create')],
        ['label'=>'Data Supplier',    'sub'=>'Nama PT (tanpa login)',    'icon'=>'ri:building-4-line',        'bg'=>'bg-purple-100',  'color'=>'text-purple-600',  'href'=>dash_route('admin.suppliers.index')],
        ['label'=>'Buat PO',          'sub'=>'Purchase order',          'icon'=>'ri:file-add-line',          'bg'=>'bg-indigo-100',  'color'=>'text-indigo-600',  'href'=>dash_route('admin.pembelian.create')],
        ['label'=>'Pengeluaran',       'sub'=>'Input biaya harian',      'icon'=>'ri:wallet-3-line',          'bg'=>'bg-red-100',     'color'=>'text-red-600',     'href'=>dash_route('admin.pengeluaran.create')],
        ['label'=>'Data Pembelian',   'sub'=>'Riwayat PO',              'icon'=>'ri:file-list-3-line',       'bg'=>'bg-primary-100', 'color'=>'text-primary-600', 'href'=>dash_route('admin.pembelian.index')],
        ['label'=>'Daftar Barang',    'sub'=>'Kelola produk',           'icon'=>'ri:shopping-bag-3-line',    'bg'=>'bg-success-100', 'color'=>'text-success-600', 'href'=>dash_route('admin.barang.index')],
        ['label'=>'Data Pengeluaran', 'sub'=>'Riwayat biaya',           'icon'=>'ri:receipt-line',           'bg'=>'bg-warning-100', 'color'=>'text-warning-600', 'href'=>dash_route('admin.pengeluaran.index')],
        ['label'=>'Surat Jalan',      'sub'=>'Buat & kelola pengiriman', 'icon'=>'ri:truck-line',              'bg'=>'bg-cyan-100',    'color'=>'text-cyan-600',    'href'=>dash_route('gudang.surat-jalan.index')],
        ['label'=>'Invoice',          'sub'=>'Tagihan pelanggan',        'icon'=>'ri:bill-line',               'bg'=>'bg-teal-100',    'color'=>'text-teal-600',    'href'=>dash_route('admin.invoice.index')],
    ];
    @endphp
    @foreach($quickLinks as $link)
    <a href="{{ $link['href'] }}" class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl p-4 hover:border-primary-300 hover:shadow-sm transition-all duration-200 block">
        <div class="w-10 h-10 {{ $link['bg'] }} {{ $link['color'] }} rounded-xl flex items-center justify-center mb-3">
            <iconify-icon icon="{{ $link['icon'] }}" class="text-xl"></iconify-icon>
        </div>
        <p class="font-semibold text-xs dark:text-white mb-0">{{ $link['label'] }}</p>
        <span class="text-secondary-light text-xs">{{ $link['sub'] }}</span>
    </a>
    @endforeach
</div>
@endunless

@endsection

@push('scripts')
@php
$pembelianLabels  = json_encode(array_column($pembelianChart, 'label'));
$pembelianValues  = json_encode(array_column($pembelianChart, 'total'));
$pembelianCounts  = json_encode(array_column($pembelianChart, 'count'));
$permintaanLabels = json_encode(array_column($permintaanChart, 'label'));
$permintaanValues = json_encode(array_column($permintaanChart, 'count'));
$profitLabels     = json_encode(array_column($profitChart, 'label'));
$profitPenjualan  = json_encode(array_column($profitChart, 'penjualan'));
$profitPembelian  = json_encode(array_column($profitChart, 'pembelian'));
$profitPengeluaran= json_encode(array_column($profitChart, 'pengeluaran'));
$profitKeuntungan = json_encode(array_column($profitChart, 'keuntungan'));
$poStatusMap      = [
    'draft' => (int) ($poStatus['draft'] ?? 0),
    'dikirim' => (int) ($poStatus['dikirim'] ?? 0),
    'sebagian_diterima' => (int) ($poStatus['sebagian_diterima'] ?? 0),
    'diterima' => (int) ($poStatus['diterima'] ?? 0),
    'dibatalkan' => (int) ($poStatus['dibatalkan'] ?? 0),
];
@endphp
<script>
// ====== Chart: Nilai Pembelian ======
const renderChart = (selector, options) => {
    const el = document.querySelector(selector);
    if (!el || typeof ApexCharts === 'undefined') return;
    try {
        new ApexCharts(el, options).render();
    } catch (err) {
        console.error(`Gagal render chart ${selector}`, err);
    }
};

const optPembelian = {
    chart: { type: 'area', height: 260, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
    series: [{ name: 'Nilai (Rp)', data: {!! $pembelianValues !!} }],
    xaxis: { categories: {!! $pembelianLabels !!}, labels: { style: { fontSize: '11px' } } },
    yaxis: {
        tickAmount: 5,
        labels: {
            formatter: v => 'Rp ' + Math.round(v).toLocaleString('id-ID'),
            style: { fontSize: '11px' }
        }
    },
    tooltip: { y: { formatter: v => 'Rp ' + Math.round(v).toLocaleString('id-ID') } },
    colors: ['#4F46E5'],
    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.45, opacityTo: 0.05, stops: [0, 100] } },
    stroke: { curve: 'smooth', width: 2 },
    dataLabels: { enabled: false },
    grid: { borderColor: '#f1f5f9', padding: { left: 0, right: 0 } },
};
renderChart('#chart-pembelian', optPembelian);

// ====== Chart: Keuntungan ======
const optProfit = {
    chart: { type: 'line', height: 320, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
    series: [
        { name: 'Penjualan', data: {!! $profitPenjualan !!} },
        { name: 'Pembelian', data: {!! $profitPembelian !!} },
        { name: 'Pengeluaran', data: {!! $profitPengeluaran !!} },
        { name: 'Keuntungan', data: {!! $profitKeuntungan !!} },
    ],
    xaxis: { categories: {!! $profitLabels !!}, labels: { style: { fontSize: '11px' } } },
    yaxis: {
        tickAmount: 5,
        labels: {
            formatter: v => {
                const n = Math.round(v);
                if (Math.abs(n) >= 1000000) return 'Rp ' + (n / 1000000).toFixed(1).replace('.', ',') + 'jt';
                if (Math.abs(n) >= 1000) return 'Rp ' + Math.round(n / 1000).toLocaleString('id-ID') + 'rb';
                return 'Rp ' + n.toLocaleString('id-ID');
            },
            style: { fontSize: '11px' }
        }
    },
    tooltip: { y: { formatter: v => 'Rp ' + Math.round(v).toLocaleString('id-ID') } },
    colors: ['#22C55E', '#4F46E5', '#EF4444', '#F59E0B'],
    stroke: { curve: 'smooth', width: [2, 2, 2, 4] },
    dataLabels: { enabled: false },
    grid: { borderColor: '#f1f5f9' },
};
renderChart('#chart-profit', optProfit);

// ====== Chart: Status PO Donut ======
const poStatusMap = @json($poStatusMap);
const statusLabelsRaw = ['draft', 'dikirim', 'sebagian_diterima', 'diterima', 'dibatalkan'];
const statusSeries = statusLabelsRaw.map(key => Number(poStatusMap[key] || 0));
const statusLabelsNice = statusLabelsRaw.map(s => s.replace(/_/g,' ').replace(/\b\w/g,c=>c.toUpperCase()));
const optPO = {
    chart: { type: 'donut', height: 200, fontFamily: 'Inter, sans-serif' },
    series: statusSeries,
    labels: statusLabelsNice,
    colors: ['#9CA3AF','#60A5FA','#FBBF24','#34D399','#F87171'],
    legend: { show: false },
    dataLabels: { enabled: false },
    plotOptions: { pie: { donut: { size: '65%' } } },
    tooltip: { y: { formatter: v => v + ' PO' } },
};
renderChart('#chart-po-status', optPO);

// ====== Chart: Permintaan ======
const optPermintaan = {
    chart: { type: 'bar', height: 260, toolbar: { show: false }, fontFamily: 'Inter, sans-serif' },
    series: [{ name: 'Permintaan', data: {!! $permintaanValues !!} }],
    xaxis: { categories: {!! $permintaanLabels !!}, labels: { style: { fontSize: '11px' } } },
    yaxis: { labels: { formatter: v => Math.round(v), style: { fontSize: '11px' } } },
    colors: ['#F59E0B'],
    plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
    dataLabels: { enabled: false },
    grid: { borderColor: '#f1f5f9' },
    tooltip: { y: { formatter: v => v + ' permintaan' } },
};
renderChart('#chart-permintaan', optPermintaan);
</script>
@endpush
