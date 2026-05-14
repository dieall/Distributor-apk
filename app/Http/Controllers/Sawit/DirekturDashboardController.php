<?php

namespace App\Http\Controllers\Sawit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DirekturDashboardController extends Controller
{
    public function index()
    {
        // Data dashboard untuk Direktur Sawit (read-only)
        $stats = [
            'total_transaksi' => 0,
            'total_pendapatan' => 0,
            'total_pembelian' => 0,
            'laba_bersih' => 0,
        ];

        return view('sawit.direktur.dashboard', compact('stats'));
    }
}
