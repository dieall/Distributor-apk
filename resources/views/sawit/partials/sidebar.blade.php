<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ url('/') }}" class="sidebar-logo flex w-full items-center justify-center gap-2">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Sistem Sawit" class="light-logo h-auto w-auto max-h-12 max-w-full object-contain object-center">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="Sistem Sawit" class="dark-logo h-auto w-auto max-h-12 max-w-full object-contain object-center">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="" class="logo-icon h-auto w-auto max-h-12 object-contain">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            @php
                $role = auth()->user()->role;
            @endphp

            {{-- ===== ADMIN SAWIT ===== --}}
            @if($role === 'adminsawit')
                <li class="sidebar-menu-group-title">Admin Sawit</li>
                <li class="{{ request()->routeIs('sawit.admin.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('sawit.admin.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Master Data</li>
                <li class="{{ request()->routeIs('sawit.admin.barang.*') ? 'active-page' : '' }}">
                    <a href="{{ route('sawit.admin.barang.index') }}">
                        <iconify-icon icon="ri:box-3-line" class="menu-icon"></iconify-icon>
                        <span>Data Barang Sawit</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('sawit.admin.perusahaan.*') ? 'active-page' : '' }}">
                    <a href="{{ route('sawit.admin.perusahaan.index') }}">
                        <iconify-icon icon="ri:building-4-line" class="menu-icon"></iconify-icon>
                        <span>Data Perusahaan</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('sawit.admin.penjual.*') ? 'active-page' : '' }}">
                    <a href="{{ route('sawit.admin.penjual.index') }}">
                        <iconify-icon icon="ri:user-line" class="menu-icon"></iconify-icon>
                        <span>Data Penjual</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Transaksi Sawit</li>
                <li class="{{ request()->routeIs('sawit.admin.penjualan.*') ? 'active-page' : '' }}">
                    <a href="{{ route('sawit.admin.penjualan.index') }}">
                        <iconify-icon icon="ri:shopping-cart-line" class="menu-icon"></iconify-icon>
                        <span>Penjualan Sawit</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('sawit.admin.pembelian.*') ? 'active-page' : '' }}">
                    <a href="{{ route('sawit.admin.pembelian.index') }}">
                        <iconify-icon icon="ri:inbox-archive-line" class="menu-icon"></iconify-icon>
                        <span>Pembelian Sawit</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('sawit.admin.surat-jalan.*') ? 'active-page' : '' }}">
                    <a href="{{ route('sawit.admin.surat-jalan.index') }}">
                        <iconify-icon icon="ri:truck-line" class="menu-icon"></iconify-icon>
                        <span>Surat Jalan</span>
                    </a>
                </li>
            @endif

            {{-- ===== ACCOUNTING SAWIT ===== --}}
            @if($role === 'accountingsawit')
                <li class="sidebar-menu-group-title">Accounting Sawit</li>
                <li class="{{ request()->routeIs('sawit.accounting.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('sawit.accounting.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Keuangan</li>
                <li>
                    <a href="#">
                        <iconify-icon icon="ri:money-dollar-circle-line" class="menu-icon"></iconify-icon>
                        <span>Pembayaran Petani</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <iconify-icon icon="ri:wallet-3-line" class="menu-icon"></iconify-icon>
                        <span>Pengeluaran</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <iconify-icon icon="ri:file-list-3-line" class="menu-icon"></iconify-icon>
                        <span>Laporan Keuangan</span>
                    </a>
                </li>
            @endif

            {{-- ===== DIREKTUR SAWIT ===== --}}
            @if($role === 'direktursawit')
                <li class="sidebar-menu-group-title">Direktur Sawit</li>
                <li class="{{ request()->routeIs('sawit.direktur.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('sawit.direktur.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Laporan</li>
                <li>
                    <a href="#">
                        <iconify-icon icon="ri:bar-chart-box-line" class="menu-icon"></iconify-icon>
                        <span>Laporan Produksi</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <iconify-icon icon="ri:line-chart-line" class="menu-icon"></iconify-icon>
                        <span>Laporan Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <iconify-icon icon="ri:pie-chart-line" class="menu-icon"></iconify-icon>
                        <span>Laporan Keuangan</span>
                    </a>
                </li>
            @endif

        </ul>
    </div>
</aside>
