<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Pengeluaran;
use App\Models\PermintaanBarang;
use App\Models\Stok;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $ttl = max(15, (int) config('cache.admin_dashboard_ttl', 90));

        $data = Cache::remember('admin.dashboard.aggregate.v1', now()->addSeconds($ttl), function () {
            return $this->buildDashboardData();
        });

        return view('admin.dashboard', $data);
    }

    /**
     * Mengumpulkan data dashboard (di-cache untuk mengurangi beban DB saat traffic tinggi).
     */
    protected function buildDashboardData(): array
    {
        $bulanIni = [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()];
        $penjualanBulanIni = DB::table('permintaan_detail')
            ->join('permintaan_barang', 'permintaan_detail.permintaan_id', '=', 'permintaan_barang.id')
            ->whereBetween('permintaan_barang.tanggal_request', $bulanIni)
            ->where('permintaan_detail.is_checked', true)
            ->sum('permintaan_detail.subtotal_jual');
        $pembelianBulanIni = Pembelian::whereBetween('tanggal', $bulanIni)->sum('total');
        $pengeluaranBulanIni = Pengeluaran::whereBetween('tanggal', $bulanIni)->sum('nominal');
        $keuntunganBulanIni = $penjualanBulanIni - $pembelianBulanIni - $pengeluaranBulanIni;

        $stats = [
            'total_barang'        => Barang::count(),
            'po_bulan_ini'        => Pembelian::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count(),
            'nilai_pembelian'     => $pembelianBulanIni,
            'nilai_penjualan'     => $penjualanBulanIni,
            'nilai_pengeluaran'   => $pengeluaranBulanIni,
            'nilai_keuntungan'    => $keuntunganBulanIni,
            'permintaan_pending'  => PermintaanBarang::where('status', 'pending')->count(),
            'stok_rendah'         => Stok::join('barang', 'stok.barang_id', '=', 'barang.id')
                                        ->whereColumn('stok.jumlah', '<=', 'barang.stok_minimum')
                                        ->count(),
            'nilai_stok'          => Stok::selectRaw('SUM(stok.jumlah * stok.harga_rata) as total')->value('total') ?? 0,
        ];

        $pembelianChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $pembelianChart[] = [
                'label' => $bulan->translatedFormat('M Y'),
                'total' => (float) Pembelian::whereYear('tanggal', $bulan->year)
                                ->whereMonth('tanggal', $bulan->month)
                                ->sum('total'),
                'count' => Pembelian::whereYear('tanggal', $bulan->year)
                                ->whereMonth('tanggal', $bulan->month)
                                ->count(),
            ];
        }

        $permintaanChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $permintaanChart[] = [
                'label' => $bulan->translatedFormat('M Y'),
                'count' => PermintaanBarang::whereYear('tanggal_request', $bulan->year)
                                ->whereMonth('tanggal_request', $bulan->month)
                                ->count(),
            ];
        }

        $profitChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $periode = [
                $bulan->copy()->startOfMonth()->toDateString(),
                $bulan->copy()->endOfMonth()->toDateString(),
            ];
            $penjualan = DB::table('permintaan_detail')
                ->join('permintaan_barang', 'permintaan_detail.permintaan_id', '=', 'permintaan_barang.id')
                ->whereBetween('permintaan_barang.tanggal_request', $periode)
                ->where('permintaan_detail.is_checked', true)
                ->sum('permintaan_detail.subtotal_jual');
            $pembelian = Pembelian::whereBetween('tanggal', $periode)->sum('total');
            $pengeluaran = Pengeluaran::whereBetween('tanggal', $periode)->sum('nominal');

            $profitChart[] = [
                'label' => $bulan->translatedFormat('M Y'),
                'penjualan' => (float) $penjualan,
                'pembelian' => (float) $pembelian,
                'pengeluaran' => (float) $pengeluaran,
                'keuntungan' => (float) ($penjualan - $pembelian - $pengeluaran),
            ];
        }

        $poStatus = Pembelian::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $stokRendah = Stok::join('barang', 'stok.barang_id', '=', 'barang.id')
            ->whereColumn('stok.jumlah', '<=', 'barang.stok_minimum')
            ->select('barang.nama', 'barang.kode', 'barang.satuan', 'barang.stok_minimum', 'stok.jumlah')
            ->orderBy('stok.jumlah', 'asc')
            ->limit(5)
            ->get();

        $recentPO = Pembelian::with('supplier')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $recentPermintaan = PermintaanBarang::with('pelanggan')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $topBarang = DB::table('permintaan_detail')
            ->join('barang', 'permintaan_detail.barang_id', '=', 'barang.id')
            ->select('barang.nama', 'barang.kode', DB::raw('SUM(permintaan_detail.jumlah_diminta) as total_diminta'))
            ->groupBy('barang.id', 'barang.nama', 'barang.kode')
            ->orderByDesc('total_diminta')
            ->limit(5)
            ->get();

        return compact(
            'stats',
            'pembelianChart',
            'permintaanChart',
            'profitChart',
            'poStatus',
            'stokRendah',
            'recentPO',
            'recentPermintaan',
            'topBarang'
        );
    }
}
