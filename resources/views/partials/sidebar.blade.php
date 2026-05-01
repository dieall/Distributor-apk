<aside class="sidebar">
    <button type="button" class="sidebar-close-btn !mt-4">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="#" class="sidebar-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="light-logo">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="Logo" class="dark-logo">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="Logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            @php $role = auth()->user()->role; @endphp

            {{-- ===== ADMIN MENU ===== --}}
            @if($role === 'admin')
                <li class="sidebar-menu-group-title">Menu Utama</li>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Master Data</li>
                <li class="{{ request()->routeIs('admin.barang.*') ? 'active-page' : '' }}">
                    <a href="{{ route('admin.barang.index') }}">
                        <iconify-icon icon="ri:box-3-line" class="menu-icon"></iconify-icon>
                        <span>Data Barang</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Pembelian</li>
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
            @endif

            {{-- ===== GUDANG MENU ===== --}}
            @if($role === 'gudang')
                <li class="sidebar-menu-group-title">Menu Utama</li>
                <li class="{{ request()->routeIs('gudang.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('gudang.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Manajemen Gudang</li>
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


            @endif

            {{-- ===== SALES MENU ===== --}}
            @if($role === 'sales')
                <li class="sidebar-menu-group-title">Menu Utama</li>
                <li class="{{ request()->routeIs('sales.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('sales.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Distribusi</li>
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

            {{-- ===== SUPPLIER MENU ===== --}}
            @if($role === 'supplier')
                <li class="sidebar-menu-group-title">Menu Utama</li>
                <li class="{{ request()->routeIs('supplier.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('supplier.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

 
            @endif

            {{-- ===== PELANGGAN MENU ===== --}}
            @if($role === 'pelanggan')
                <li class="sidebar-menu-group-title">Menu Utama</li>
                <li class="{{ request()->routeIs('pelanggan.dashboard') ? 'active-page' : '' }}">
                    <a href="{{ route('pelanggan.dashboard') }}">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Permintaan Barang</li>
                <li class="{{ request()->routeIs('pelanggan.permintaan.index') ? 'active-page' : '' }}">
                    <a href="{{ route('pelanggan.permintaan.index') }}">
                        <iconify-icon icon="ri:file-list-line" class="menu-icon"></iconify-icon>
                        <span>Daftar Permintaan</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('pelanggan.permintaan.create') ? 'active-page' : '' }}">
                    <a href="{{ route('pelanggan.permintaan.create') }}">
                        <iconify-icon icon="ri:add-circle-line" class="menu-icon"></iconify-icon>
                        <span>Buat Permintaan</span>
                    </a>
                </li>

 
            @endif

        </ul>
    </div>
</aside>
