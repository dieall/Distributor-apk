<div class="navbar-header border-b border-neutral-200 dark:border-neutral-600">
    <div class="flex items-center justify-between">
        <div class="col-auto">
            <div class="flex flex-wrap items-center gap-[16px]">
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon active"></iconify-icon>
                </button>
                <button type="button" class="sidebar-mobile-toggle flex !leading-[0]">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon !text-[30px]"></iconify-icon>
                </button>
                <form class="navbar-search">
                    <input type="text" name="search" placeholder="Cari sesuatu...">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form>
            </div>
        </div>

        <div class="col-auto">
            <div class="flex flex-wrap items-center gap-3">

                {{-- Notifikasi --}}
                <button data-dropdown-toggle="dropdownNotification"
                    class="has-indicator w-10 h-10 bg-neutral-200 dark:bg-neutral-700 rounded-full flex justify-center items-center"
                    type="button">
                    <iconify-icon icon="iconoir:bell" class="text-neutral-900 dark:text-white text-xl"></iconify-icon>
                </button>
                <div id="dropdownNotification"
                    class="z-10 hidden bg-white dark:bg-neutral-700 rounded-2xl overflow-hidden shadow-lg max-w-[394px] w-full">
                    <div
                        class="py-3 px-4 rounded-lg bg-primary-50 dark:bg-primary-600/25 m-4 flex items-center justify-between gap-2">
                        <h6 class="text-lg text-neutral-900 font-semibold mb-0">Notifikasi</h6>
                        <span
                            class="w-10 h-10 bg-white dark:bg-neutral-600 text-primary-600 dark:text-white font-bold flex justify-center items-center rounded-full">0</span>
                    </div>
                    <div class="text-center py-6 px-4 text-sm text-secondary-light">
                        Tidak ada notifikasi
                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <button data-dropdown-toggle="dropdownProfile"
                    class="flex justify-center items-center rounded-full" type="button">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="Avatar"
                            class="w-10 h-10 object-fit-cover rounded-full">
                    @else
                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <iconify-icon icon="ri:user-3-line" class="text-primary-600 text-xl"></iconify-icon>
                        </div>
                    @endif
                </button>
                <div id="dropdownProfile"
                    class="z-10 hidden bg-white dark:bg-neutral-700 rounded-lg shadow-lg dropdown-menu-sm p-3">
                    <div
                        class="py-3 px-4 rounded-lg bg-primary-50 dark:bg-primary-600/25 mb-4 flex items-center justify-between gap-2">
                        <div>
                            <h6 class="text-lg text-neutral-900 font-semibold mb-0">{{ auth()->user()->name }}</h6>
                            <span class="text-neutral-500">{{ auth()->user()->role_label }}</span>
                        </div>
                        <button type="button" class="hover:text-danger-600">
                            <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                        </button>
                    </div>
                    <div class="max-h-[400px] overflow-y-auto scroll-sm pe-2">
                        <ul class="flex flex-col">
                            <li>
                                <a class="text-black dark:text-white px-0 py-2 hover:text-primary-600 flex items-center gap-4"
                                    href="#">
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon>
                                    Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="text-black dark:text-white px-0 py-2 hover:text-primary-600 flex items-center gap-4"
                                    href="#">
                                    <iconify-icon icon="icon-park-outline:setting-two"
                                        class="icon text-xl"></iconify-icon>
                                    Pengaturan
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="text-black dark:text-white px-0 py-2 hover:text-danger-600 flex items-center gap-4 w-full text-start">
                                        <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon>
                                        Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
