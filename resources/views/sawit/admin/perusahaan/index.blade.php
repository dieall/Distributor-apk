@extends('layouts.sawit')
@section('title', 'Data Perusahaan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Data Perusahaan</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Kelola data perusahaan pembeli sawit</p>
    </div>
    <ul class="flex items-center gap-[6px] text-sm">
        <li class="font-medium"><a href="{{ route('sawit.admin.dashboard') }}" class="hover:text-primary-600">Dashboard</a></li>
        <li class="text-neutral-400">/</li>
        <li class="text-neutral-500 font-medium">Perusahaan</li>
    </ul>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="px-6 py-4 border-b border-neutral-200 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800 flex flex-wrap items-center justify-between gap-3">
        <h6 class="font-semibold mb-0 dark:text-white text-sm">Daftar Perusahaan ({{ $perusahaan->total() }})</h6>
        <a href="{{ route('sawit.admin.perusahaan.create') }}" class="px-4 py-2 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition flex items-center gap-2"><iconify-icon icon="ri:add-line"></iconify-icon> Tambah Perusahaan</a>
    </div>
    <div class="p-6">
        <form method="GET" class="mb-6">
            <div class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama perusahaan..." class="flex-1 px-3 py-2 rounded-lg border border-neutral-300 text-sm bg-white dark:bg-neutral-800 dark:text-white focus:ring-2 focus:ring-success-500 focus:outline-none">
                <button type="submit" class="px-4 py-2 rounded-lg bg-success-600 hover:bg-success-700 text-white text-sm font-medium transition">Cari</button>
                <a href="{{ route('sawit.admin.perusahaan.index') }}" class="px-4 py-2 rounded-lg border border-neutral-300 text-sm font-medium text-neutral-700 hover:bg-neutral-100 transition">Reset</a>
            </div>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-200 dark:border-neutral-600">
                        <th class="text-left py-3 px-3 text-xs font-semibold text-neutral-600">Nama Perusahaan</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-neutral-600">Alamat</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-neutral-600">Telepon</th>
                        <th class="text-left py-3 px-3 text-xs font-semibold text-neutral-600">Contact Person</th>
                        <th class="text-center py-3 px-3 text-xs font-semibold text-neutral-600">Status</th>
                        <th class="text-center py-3 px-3 text-xs font-semibold text-neutral-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perusahaan as $item)
                    <tr class="border-b border-neutral-100 dark:border-neutral-600 hover:bg-neutral-50 dark:hover:bg-neutral-600/50">
                        <td class="py-3 px-3 text-sm font-medium dark:text-white">{{ $item->nama_perusahaan }}</td>
                        <td class="py-3 px-3 text-sm text-secondary-light">{{ $item->alamat ?: '-' }}</td>
                        <td class="py-3 px-3 text-sm text-secondary-light">{{ $item->telepon ?: '-' }}</td>
                        <td class="py-3 px-3 text-sm dark:text-white">{{ $item->contact_person ?: '-' }}</td>
                        <td class="py-3 px-3 text-center"><span class="text-xs px-2 py-1 rounded {{ $item->is_active?'bg-success-100 text-success-600':'bg-danger-100 text-danger-600' }}">{{ $item->is_active?'Aktif':'Nonaktif' }}</span></td>
                        <td class="py-3 px-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('sawit.admin.perusahaan.edit', $item) }}" class="w-7 h-7 rounded bg-warning-100 text-warning-600 hover:bg-warning-200 flex items-center justify-center"><iconify-icon icon="ri:edit-line" class="text-sm"></iconify-icon></a>
                                <form action="{{ route('sawit.admin.perusahaan.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')
                                    <button class="w-7 h-7 rounded bg-danger-100 text-danger-600 hover:bg-danger-200 flex items-center justify-center"><iconify-icon icon="ri:delete-bin-line" class="text-sm"></iconify-icon></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-8 text-center text-secondary-light">Belum ada data perusahaan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($perusahaan->hasPages())<div class="mt-6">{{ $perusahaan->links() }}</div>@endif
    </div>
</div>
@endsection
