<?php

namespace App\Http\Controllers\Sawit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountingDashboardController extends Controller
{
    public function index()
    {
        // Data dashboard untuk Accounting Sawit
        $stats = [
            'total_pemasukan' => 0,
            'total_pengeluaran' => 0,
            'laba_rugi' => 0,
            'pending_approval' => 0,
        ];

        return view('sawit.accounting.dashboard', compact('stats'));
    }
}
