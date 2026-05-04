<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Tampilan dan data sama persis dengan dashboard admin.
     */
    public function index()
    {
        return app(AdminDashboardController::class)->index();
    }
}
