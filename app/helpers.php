<?php

use App\Models\AppNotification;
use App\Models\User;

if (! function_exists('notif_kirim')) {
    /**
     * Kirim notifikasi ke satu user.
     */
    function notif_kirim(
        int    $userId,
        string $title,
        string $body    = '',
        string $url     = '',
        string $icon    = 'ri:notification-3-line',
        string $color   = 'primary'
    ): void {
        AppNotification::create([
            'user_id' => $userId,
            'title'   => $title,
            'body'    => $body,
            'icon'    => $icon,
            'color'   => $color,
            'url'     => $url ?: '',
        ]);
    }
}

if (! function_exists('notif_kirim_ke_role')) {
    /**
     * Kirim notifikasi ke semua user aktif dengan role tertentu.
     * @param string|array $roles
     */
    function notif_kirim_ke_role(
        string|array $roles,
        string $title,
        string $body  = '',
        string $url   = '',
        string $icon  = 'ri:notification-3-line',
        string $color = 'primary'
    ): void {
        $roles = (array) $roles;
        User::whereIn('role', $roles)
            ->where('is_active', true)
            ->pluck('id')
            ->each(fn ($id) => notif_kirim($id, $title, $body, $url, $icon, $color));
    }
}

if (! function_exists('csv_tanggal_untuk_excel')) {
    /**
     * Tanggal untuk export CSV/TSV: format ISO (YYYY-MM-DD) agar Excel tidak menampilkan ####
     * karena lebar kolom atau salah interpretasi locale.
     */
    function csv_tanggal_untuk_excel(\DateTimeInterface|\Carbon\Carbon|string|null $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }
        if (! $value instanceof \DateTimeInterface) {
            $value = \Illuminate\Support\Carbon::parse($value);
        }

        return $value->format('Y-m-d');
    }
}

if (! function_exists('fin_route')) {
    /** Route admin vs purchasing untuk modul PO & pengeluaran. */
    function fin_route(string $suffix, mixed $parameters = []): string
    {
        $prefix = auth()->check() && auth()->user()->role === 'purchasing' ? 'purchasing' : 'admin';

        return route($prefix.'.'.$suffix, $parameters);
    }
}

if (! function_exists('fin_route_n')) {
    function fin_route_n(string $suffix): string
    {
        $prefix = auth()->check() && auth()->user()->role === 'purchasing' ? 'purchasing' : 'admin';

        return $prefix.'.'.$suffix;
    }
}

if (! function_exists('dashboard_home_route')) {
    function dashboard_home_route(): string
    {
        if (! auth()->check()) {
            return route('login');
        }

        return auth()->user()->role === 'purchasing'
            ? route('purchasing.dashboard')
            : route('admin.dashboard');
    }
}

if (! function_exists('dash_route')) {
    /**
     * URL seperti route admin; untuk purchasing, PO & pengeluaran memakai prefix purchasing.
     */
    function dash_route(string $adminRouteName, mixed $parameters = []): string
    {
        if (! auth()->check()) {
            return '#';
        }

        $map = [
            'admin.pembelian.index'   => 'purchasing.pembelian.index',
            'admin.pembelian.create'  => 'purchasing.pembelian.create',
            'admin.pembelian.show'    => 'purchasing.pembelian.show',
            'admin.pembelian.status'  => 'purchasing.pembelian.status',
            'admin.pembelian.export'  => 'purchasing.pembelian.export',
            'admin.pengeluaran.index'   => 'purchasing.pengeluaran.index',
            'admin.pengeluaran.create'  => 'purchasing.pengeluaran.create',
            'admin.pengeluaran.show'    => 'purchasing.pengeluaran.show',
            'admin.pengeluaran.destroy' => 'purchasing.pengeluaran.destroy',
            'admin.pengeluaran.export'  => 'purchasing.pengeluaran.export',
            'admin.pengeluaran.aset.index' => 'purchasing.pengeluaran.aset.index',
            'admin.pengeluaran.aset.edit' => 'purchasing.pengeluaran.aset.edit',
            'admin.pengeluaran.aset.update' => 'purchasing.pengeluaran.aset.update',
        ];

        if (auth()->user()->role === 'purchasing' && isset($map[$adminRouteName])) {
            return route($map[$adminRouteName], $parameters);
        }

        return route($adminRouteName, $parameters);
    }
}

if (! function_exists('format_qty_id')) {
    /**
     * Format kuantitas untuk tampilan ID: tanpa ",00" jika bilangan bulat; pecahan dipotong nol di belakang.
     */
    function format_qty_id(float|int|string|null $value): string
    {
        $n = (float) $value;
        if (! is_finite($n)) {
            return '0';
        }
        $s = number_format($n, 2, ',', '.');

        return rtrim(rtrim($s, '0'), ',') ?: '0';
    }
}
