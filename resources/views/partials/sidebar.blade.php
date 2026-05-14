<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ url('/') }}" class="sidebar-logo flex w-full items-center justify-center gap-2">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Area Group Sejahtera" class="light-logo h-auto w-auto max-h-12 max-w-full object-contain object-center">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="Area Group Sejahtera" class="dark-logo h-auto w-auto max-h-12 max-w-full object-contain object-center">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="" class="logo-icon h-auto w-auto max-h-12 object-contain">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            @php
                $role = auth()->user()->role;
                $isAdmin = $role === 'admin' || $role === 'direktur';
            @endphp

            {{-- ===== ADMIN (Administrator) ===== --}}
            @if($isAdmin)
                <li class="sidebar-menu-group-title">Administrator</li>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard Admin</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Master Data</li>
                <li class="{{ request()->routeIs('admin.barang.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.barang.index') }}">
                        <iconify-icon icon="ri:box-3-line" class="menu-icon"></iconify-icon>
                        <span>Data Barang</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.suppliers.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.suppliers.index') }}">
                        <iconify-icon icon="ri:building-4-line" class="menu-icon"></iconify-icon>
                        <span>Data Supplier</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Keuangan &amp; Pembelian</li>
                <li class="{{ request()->routeIs('admin.pembelian.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.pembelian.index') }}">
                        <iconify-icon icon="ri:shopping-cart-2-line" class="menu-icon"></iconify-icon>
                        <span>Purchase Order</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.pengeluaran.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.pengeluaran.index') }}">
                        <iconify-icon icon="ri:wallet-3-line" class="menu-icon"></iconify-icon>
                        <span>Pengeluaran</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Pengiriman &amp; Tagihan</li>
                <li class="{{ request()->routeIs('admin.invoice.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.invoice.index') }}">
                        <iconify-icon icon="ri:bill-line" class="menu-icon"></iconify-icon>
                        <span>Invoice Pelanggan</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Sistem</li>
                <li class="{{ request()->routeIs('admin.users.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <iconify-icon icon="ri:group-line" class="menu-icon"></iconify-icon>
                        <span>Manajemen User</span>
                    </a>
                </li>
            @endif

            {{-- ===== GUDANG ===== --}}
            @if($isAdmin || $role === 'gudang')
                <li class="sidebar-menu-group-title">{{ $isAdmin ? 'Akses Gudang' : 'Menu Utama' }}</li>
                <li class="{{ request()->routeIs('gudang.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('gudang.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>{{ $isAdmin ? 'Dashboard Gudang' : 'Dashboard' }}</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">{{ $isAdmin ? 'Stok & Penerimaan' : 'Manajemen Gudang' }}</li>
                <li class="{{ request()->routeIs('gudang.stok.*') ? 'active-page' : '' }}">
                    <a href="{{ route('gudang.stok.index') }}">
                        <iconify-icon icon="ri:store-2-line" class="menu-icon"></iconify-icon>
                        <span>Stok Barang</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('gudang.penerimaan.*') ? 'active-page' : '' }}">
                    <a href="{{ route('gudang.penerimaan.index') }}">
                        <iconify-icon icon="ri:inbox-archive-line" class="menu-icon"></iconify-icon>
                        <span>Penerimaan Barang</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('gudang.pengeluaran-barang.*') ? 'active-page' : '' }}">
                    <a href="{{ route('gudang.pengeluaran-barang.index') }}">
                        <iconify-icon icon="ri:logout-box-r-line" class="menu-icon"></iconify-icon>
                        <span>Pengeluaran Barang</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('gudang.surat-jalan.*') ? 'active-page' : '' }}">
                    <a href="{{ route('gudang.surat-jalan.index') }}">
                        <iconify-icon icon="ri:truck-line" class="menu-icon"></iconify-icon>
                        <span>Surat Jalan</span>
                    </a>
                </li>
            @endif

            {{-- ===== SALES ===== --}}
            @if($isAdmin || $role === 'sales')
                <li class="sidebar-menu-group-title">{{ $isAdmin ? 'Akses Sales' : 'Menu Utama' }}</li>
                <li class="{{ request()->routeIs('sales.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('sales.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>{{ $isAdmin ? 'Dashboard Sales' : 'Dashboard' }}</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">{{ $isAdmin ? 'Permintaan & Kirim' : 'Distribusi' }}</li>
                <li class="{{ request()->routeIs('sales.permintaan.*') ? 'active-page' : '' }}">
                    <a href="{{ route('sales.permintaan.index') }}">
                        <iconify-icon icon="ri:file-list-3-line" class="menu-icon"></iconify-icon>
                        <span>Permintaan Barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sales.permintaan.index', ['status' => 'pending']) }}">
                        <iconify-icon icon="ri:time-line" class="menu-icon"></iconify-icon>
                        <span>Menunggu Proses</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sales.permintaan.index', ['status' => 'siap_kirim']) }}">
                        <iconify-icon icon="ri:truck-line" class="menu-icon"></iconify-icon>
                        <span>Siap Dikirim</span>
                    </a>
                </li>
            @endif

            {{-- ===== PURCHASING ===== --}}
            @if($role === 'purchasing')
                <li class="sidebar-menu-group-title">Purchasing</li>
                <li class="{{ request()->routeIs('purchasing.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('purchasing.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">PO &amp; pembayaran</li>
                <li class="{{ request()->routeIs('purchasing.pembelian.*') ? 'active-page' : '' }}">
                    <a href="{{ route('purchasing.pembelian.index') }}">
                        <iconify-icon icon="ri:shopping-cart-2-line" class="menu-icon"></iconify-icon>
                        <span>Purchase Order</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Pembayaran pengeluaran</li>
                <li class="{{ request()->routeIs('purchasing.pengeluaran.*') ? 'active-page' : '' }}">
                    <a href="{{ route('purchasing.pengeluaran.index') }}">
                        <iconify-icon icon="ri:wallet-3-line" class="menu-icon"></iconify-icon>
                        <span>Pengeluaran</span>
                    </a>
                </li>
            @endif

            {{-- ===== PELANGGAN ===== --}}
            @if($isAdmin || $role === 'pelanggan')
                <li class="sidebar-menu-group-title">{{ $isAdmin ? 'Akses Pelanggan' : 'Menu Utama' }}</li>
                <li class="{{ request()->routeIs('pelanggan.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('pelanggan.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>{{ $isAdmin ? 'Dashboard Pelanggan' : 'Dashboard' }}</span>
                    </a>
                </li>


            @endif

        </ul>
    </div>
</aside>
