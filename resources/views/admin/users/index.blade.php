@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white text-lg">Manajemen User</h6>
        <p class="text-secondary-light text-sm mb-0">Kelola akun login dan hak akses pengguna sistem</p>
    </div>
    <ul class="flex items-center gap-[6px]">
        <li class="font-medium"><a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 hover:text-primary-600"><iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard</a></li>
        <li>-</li>
        <li class="font-medium">Manajemen User</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    {{-- Header + Filter --}}
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <div class="relative">
                <iconify-icon icon="ri:search-line" class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary-light text-sm"></iconify-icon>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..."
                    class="pl-9 pr-4 py-2 text-sm border border-neutral-300 dark:border-neutral-500 dark:bg-neutral-800 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300 w-56">
            </div>
            <select name="role" class="text-sm border border-neutral-300 dark:border-neutral-500 dark:bg-neutral-800 dark:text-white rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-300">
                <option value="">Semua Role</option>
                @foreach($roles as $r)
                    <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-primary-600 px-4 py-2 rounded-lg text-sm font-medium">Filter</button>
            @if(request()->hasAny(['search','role']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-lg text-sm">Reset</a>
            @endif
        </form>
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary-600 px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
            <iconify-icon icon="ri:user-add-line" class="text-base"></iconify-icon> Tambah User
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="table w-full text-sm">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-light uppercase tracking-wide">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-light uppercase tracking-wide">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-light uppercase tracking-wide">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-light uppercase tracking-wide">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-light uppercase tracking-wide">No. HP</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-light uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-secondary-light uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @forelse($users as $user)
                @php
                $roleBadges = [
                    'admin'     => 'bg-primary-100 text-primary-700',
                    'direktur'  => 'bg-indigo-100 text-indigo-700',
                    'gudang'    => 'bg-success-100 text-success-700',
                    'sales'     => 'bg-warning-100 text-warning-700',
                    'purchasing'=> 'bg-blue-100 text-blue-700',
                    'pelanggan' => 'bg-neutral-100 text-neutral-700',
                ];
                $badge = $roleBadges[$user->role] ?? 'bg-neutral-100 text-neutral-600';
                @endphp
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-colors">
                    <td class="px-6 py-3 text-secondary-light">{{ $users->firstItem() + $loop->index }}</td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold dark:text-white mb-0 text-sm">{{ $user->name }}</p>
                                @if($user->id === auth()->id())
                                    <span class="text-xs text-primary-500">(Anda)</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-secondary-light">{{ $user->email }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badge }}">{{ $user->role_label }}</span>
                    </td>
                    <td class="px-6 py-3 text-secondary-light">{{ $user->phone ?? '-' }}</td>
                    <td class="px-6 py-3">
                        @if($user->is_active)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-success-100 text-success-700">Aktif</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" title="Edit"
                               class="w-8 h-8 rounded-lg bg-warning-100 text-warning-600 hover:bg-warning-200 flex items-center justify-center transition-colors">
                                <iconify-icon icon="ri:edit-line" class="text-sm"></iconify-icon>
                            </a>

                            @if($user->id !== auth()->id())
                            {{-- Toggle Active --}}
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                    class="w-8 h-8 rounded-lg {{ $user->is_active ? 'bg-neutral-100 text-neutral-500 hover:bg-red-100 hover:text-red-600' : 'bg-success-100 text-success-600 hover:bg-success-200' }} flex items-center justify-center transition-colors">
                                    <iconify-icon icon="{{ $user->is_active ? 'ri:user-forbid-line' : 'ri:user-follow-line' }}" class="text-sm"></iconify-icon>
                                </button>
                            </form>

                            {{-- Delete --}}
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus"
                                    class="w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center transition-colors">
                                    <iconify-icon icon="ri:delete-bin-line" class="text-sm"></iconify-icon>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-secondary-light">
                        <iconify-icon icon="ri:user-search-line" class="text-4xl block mx-auto mb-2 text-neutral-300"></iconify-icon>
                        Tidak ada user ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-neutral-100 dark:border-neutral-600">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
