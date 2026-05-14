@php
    $notifList = auth()->check()
        ? \App\Models\AppNotification::where('user_id', auth()->id())
            ->latest()
            ->limit(20)
            ->get()
        : collect();
    $unreadCount = $notifList->whereNull('read_at')->count();
@endphp

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
                <div class="flex items-center gap-2">
                    <iconify-icon icon="ri:plant-line" class="text-success-600 text-2xl"></iconify-icon>
                    <h5 class="text-lg font-semibold text-neutral-900 dark:text-white mb-0">Sistem Sawit</h5>
                </div>
            </div>
        </div>

        <div class="col-auto">
            <div class="flex flex-wrap items-center gap-3">

                {{-- Notifikasi --}}
                <div class="relative">
                    <button data-dropdown-toggle="dropdownNotification" id="btn-notif"
                        class="relative w-10 h-10 bg-neutral-200 dark:bg-neutral-700 rounded-full flex justify-center items-center"
                        type="button">
                        <iconify-icon icon="iconoir:bell" class="text-neutral-900 dark:text-white text-xl"></iconify-icon>
                        @if($unreadCount > 0)
                        <span id="notif-badge"
                            class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-danger-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-0.5 leading-none">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                        @else
                        <span id="notif-badge" class="hidden absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-danger-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-0.5 leading-none"></span>
                        @endif
                    </button>

                    <div id="dropdownNotification"
                        class="z-50 hidden bg-white dark:bg-neutral-700 rounded-2xl overflow-hidden shadow-lg w-[380px] max-w-[95vw]">

                        {{-- Header --}}
                        <div class="py-3 px-4 bg-primary-50 dark:bg-primary-600/25 m-4 rounded-xl flex items-center justify-between gap-2">
                            <div>
                                <h6 class="text-base font-semibold mb-0 text-neutral-900 dark:text-white">Notifikasi</h6>
                                <p class="text-xs text-secondary-light mb-0 mt-0.5" id="notif-subtitle">
                                    {{ $unreadCount > 0 ? $unreadCount . ' belum dibaca' : 'Semua sudah dibaca' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span id="notif-count-badge"
                                    class="w-9 h-9 bg-white dark:bg-neutral-600 text-primary-600 dark:text-white font-bold flex justify-center items-center rounded-full text-sm">
                                    {{ $unreadCount }}
                                </span>
                                @if($unreadCount > 0)
                                <button type="button" id="btn-read-all" title="Tandai semua dibaca"
                                    class="w-8 h-8 rounded-lg bg-white dark:bg-neutral-600 text-secondary-light hover:text-primary-600 flex items-center justify-center text-sm">
                                    <iconify-icon icon="ri:check-double-line"></iconify-icon>
                                </button>
                                @endif
                            </div>
                        </div>

                        {{-- List --}}
                        <div class="max-h-[380px] overflow-y-auto divide-y divide-neutral-100 dark:divide-neutral-600" id="notif-list">
                            @forelse($notifList as $n)
                            <div class="notif-item flex items-start gap-3 px-4 py-3 cursor-pointer hover:bg-neutral-50 dark:hover:bg-neutral-600/40 transition {{ $n->isUnread() ? 'bg-primary-50/60 dark:bg-primary-900/20' : '' }}"
                                data-id="{{ $n->id }}"
                                data-url="{{ $n->url }}"
                                data-read="{{ $n->read_at ? 1 : 0 }}">
                                <div class="w-9 h-9 rounded-full bg-{{ $n->color }}-100 text-{{ $n->color }}-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <iconify-icon icon="{{ $n->icon }}" class="text-base"></iconify-icon>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold dark:text-white mb-0 leading-tight {{ $n->isUnread() ? '' : 'text-neutral-500 dark:text-neutral-400' }}">{{ $n->title }}</p>
                                    @if($n->body)
                                    <p class="text-xs text-secondary-light mb-0 mt-0.5 truncate">{{ $n->body }}</p>
                                    @endif
                                    <p class="text-[10px] text-neutral-400 mb-0 mt-0.5">{{ $n->created_at->diffForHumans() }}</p>
                                </div>
                                @if($n->isUnread())
                                <span class="w-2 h-2 bg-primary-500 rounded-full flex-shrink-0 mt-2"></span>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-10 px-4" id="notif-empty">
                                <iconify-icon icon="ri:notification-off-line" class="text-3xl text-neutral-300 block mx-auto mb-2"></iconify-icon>
                                <p class="text-sm text-secondary-light mb-0">Tidak ada notifikasi</p>
                            </div>
                            @endforelse
                        </div>

                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <button data-dropdown-toggle="dropdownProfile"
                    class="flex justify-center items-center rounded-full" type="button">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="Avatar"
                            class="w-10 h-10 object-fit-cover rounded-full">
                    @else
                        <div class="w-10 h-10 rounded-full bg-success-100 flex items-center justify-center">
                            <iconify-icon icon="ri:user-3-line" class="text-success-600 text-xl"></iconify-icon>
                        </div>
                    @endif
                </button>
                <div id="dropdownProfile"
                    class="z-10 hidden bg-white dark:bg-neutral-700 rounded-lg shadow-lg dropdown-menu-sm p-3">
                    <div
                        class="py-3 px-4 rounded-lg bg-success-50 dark:bg-success-600/25 mb-4 flex items-center justify-between gap-2">
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
                                <a class="text-black dark:text-white px-0 py-2 hover:text-success-600 flex items-center gap-4"
                                    href="{{ route('profile.index') }}">
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon>
                                    Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="text-black dark:text-white px-0 py-2 hover:text-success-600 flex items-center gap-4"
                                    href="{{ route('profile.settings') }}">
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

@push('scripts')
<script>
(function () {
    const MARK_READ_BASE  = '{{ url("notifications") }}';
    const MARK_ALL_URL    = '{{ route("notif.readAll") }}';
    const CSRF            = '{{ csrf_token() }}';

    async function patchJson(url) {
        return fetch(url, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
    }

    function updateCountDisplay(newCount) {
        const badge    = document.getElementById('notif-badge');
        const countBdg = document.getElementById('notif-count-badge');
        const subtitle = document.getElementById('notif-subtitle');
        if (badge) {
            if (newCount > 0) {
                badge.textContent = newCount > 99 ? '99+' : newCount;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
        if (countBdg) countBdg.textContent = newCount;
        if (subtitle) subtitle.textContent = newCount > 0 ? newCount + ' belum dibaca' : 'Semua sudah dibaca';
    }

    // Klik item notifikasi → mark as read → redirect ke url
    document.querySelectorAll('.notif-item').forEach(function (el) {
        el.addEventListener('click', async function () {
            const id   = el.dataset.id;
            const url  = el.dataset.url;
            const read = el.dataset.read === '1';

            if (!read) {
                await patchJson(MARK_READ_BASE + '/' + id + '/read');
                el.classList.remove('bg-primary-50/60', 'dark:bg-primary-900/20');
                const dot = el.querySelector('.bg-primary-500');
                if (dot) dot.remove();
                el.dataset.read = '1';

                const badge = document.getElementById('notif-badge');
                const cur = parseInt(badge ? badge.textContent : '0') || 0;
                updateCountDisplay(Math.max(0, cur - 1));
            }

            if (url && url !== '') {
                window.location.href = url;
            }
        });
    });

    // Tandai semua dibaca
    const btnAll = document.getElementById('btn-read-all');
    if (btnAll) {
        btnAll.addEventListener('click', async function (e) {
            e.stopPropagation();
            await patchJson(MARK_ALL_URL);
            document.querySelectorAll('.notif-item').forEach(function (el) {
                el.classList.remove('bg-primary-50/60', 'dark:bg-primary-900/20');
                const dot = el.querySelector('.bg-primary-500');
                if (dot) dot.remove();
                el.dataset.read = '1';
            });
            updateCountDisplay(0);
            btnAll.remove();
        });
    }
})();
</script>
@endpush
