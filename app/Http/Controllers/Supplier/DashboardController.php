<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('supplier.dashboard');
    }
}
