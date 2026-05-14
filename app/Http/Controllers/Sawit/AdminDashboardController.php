<?php

namespace App\Http\Controllers\Sawit;

use App\Http\Controllers\Controller;
use App\Models\SawitBarang;
use App\Models\SawitPenjualan;
use App\Models\SawitPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total barang sawit
        $totalBarang = SawitBarang::active()->count();
        
        // Total stok kg sawit
        $totalStokKg = SawitBarang::active()->sum('total_kg');
        
        // Total penjualan hari ini
        $penjualanHariIni = SawitPenjualan::whereDate('tanggal', today())
            ->where('status', 'done')
            ->count();
        
        // Total pembelian hari ini
        $pembelianHariIni = SawitPembelian::whereDate('tanggal', today())
            ->where('status', 'done')
            ->count();
        
        // Total pendapatan penjualan (done)
        $totalPendapatan = SawitPenjualan::where('status', 'done')
            ->sum('total_harga');
        
        // Total biaya pembelian (done)
        $totalBiayaPembelian = SawitPembelian::where('status', 'done')
            ->sum('total_harga');
        
        // Total invoice draft
        $totalInvoiceDraft = SawitPenjualan::where('status', 'draft')->count();
        
        // Total invoice done
        $totalInvoiceDone = SawitPenjualan::where('status', 'done')->count();
        
        // Total pembayaran belum lunas
        $totalBelumLunas = SawitPenjualan::where('status', 'done')
            ->whereRaw('(total_harga - pembayaran_invoice - potongan) > 0')
            ->count();
        
        // Total potongan/refaksi
        $totalRefaksiPenjualan = SawitPenjualan::where('status', 'done')->sum('refaksi');
        $totalRefaksiPembelian = SawitPembelian::where('status', 'done')->sum('refaksi');
        
        // Grafik penjualan per bulan (6 bulan terakhir)
        $grafikPenjualan = SawitPenjualan::where('status', 'done')
            ->where('tanggal', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, SUM(total_harga) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
        
        // Grafik pembelian per bulan (6 bulan terakhir)
        $grafikPembelian = SawitPembelian::where('status', 'done')
            ->where('tanggal', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, SUM(total_harga) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
        
        // Transaksi penjualan terbaru
        $penjualanTerbaru = SawitPenjualan::with(['perusahaan', 'barang'])
            ->latest('tanggal')
            ->limit(5)
            ->get();
        
        // Transaksi pembelian terbaru
        $pembelianTerbaru = SawitPembelian::with(['barang'])
            ->latest('tanggal')
            ->limit(5)
            ->get();
        
        // Stok per jenis sawit
        $stokPerJenis = SawitBarang::active()
            ->orderBy('total_kg', 'desc')
            ->get();

        $stats = [
            'total_barang' => $totalBarang,
            'total_stok_kg' => $totalStokKg,
            'penjualan_hari_ini' => $penjualanHariIni,
            'pembelian_hari_ini' => $pembelianHariIni,
            'total_pendapatan' => $totalPendapatan,
            'total_biaya_pembelian' => $totalBiayaPembelian,
            'total_invoice_draft' => $totalInvoiceDraft,
            'total_invoice_done' => $totalInvoiceDone,
            'total_belum_lunas' => $totalBelumLunas,
            'total_refaksi_penjualan' => $totalRefaksiPenjualan,
            'total_refaksi_pembelian' => $totalRefaksiPembelian,
        ];

        return view('sawit.admin.dashboard', compact(
            'stats',
            'grafikPenjualan',
            'grafikPembelian',
            'penjualanTerbaru',
            'pembelianTerbaru',
            'stokPerJenis'
        ));
    }
}
