@extends('layouts.app')
@section('title', 'Data Supplier')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-2 mb-6">
    <div>
        <h6 class="font-semibold mb-0 dark:text-white">Data Supplier</h6>
        <p class="text-secondary-light text-sm mb-0 mt-1">Daftar nama PT / supplier untuk PO. Hanya admin yang dapat menambah atau mengubah.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.suppliers.create') }}" class="px-4 py-2 rounded-lg bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium flex items-center gap-2">
            <iconify-icon icon="ri:add-line"></iconify-icon> Tambah Supplier
        </a>
    </div>
</div>

@include('partials.alert')

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl mb-6">
    <div class="p-5">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="text-sm font-medium mb-1 block dark:text-white">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama PT..."
                    class="w-full px-3.5 py-2.5 rounded-lg border border-neutral-300 dark:border-neutral-500 text-sm bg-white dark:bg-neutral-800 dark:text-white">
            </div>
            <button type="submit" class="px-4 py-2.5 rounded-lg bg-neutral-900 dark:bg-primary-600 text-white text-sm font-medium">Cari</button>
        </form>
    </div>
</div>

<div class="card shadow-none border border-neutral-200 dark:border-neutral-600 dark:bg-neutral-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 dark:bg-neutral-800">
                    <th class="text-left px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Nama / PT</th>
                    <th class="text-center px-6 py-3 text-xs font-semibold text-secondary-light uppercase">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-600">
                @forelse($suppliers as $item)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-600/50">
                    <td class="px-6 py-3.5 font-semibold dark:text-white">{{ $item->name }}</td>
                    <td class="px-6 py-3.5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.suppliers.edit', $item) }}" class="w-8 h-8 rounded-lg bg-primary-100 text-primary-600 hover:bg-primary-200 inline-flex items-center justify-center" title="Ubah">
                                <iconify-icon icon="ri:pencil-line"></iconify-icon>
                            </a>
                            <form action="{{ route('admin.suppliers.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus supplier ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-100 text-red-500 hover:bg-red-200 inline-flex items-center justify-center" title="Hapus">
                                    <iconify-icon icon="ri:delete-bin-line"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="2" class="px-6 py-10 text-center text-secondary-light">Belum ada supplier. Tambahkan PT dari tombol di atas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4">{{ $suppliers->links() }}</div>
</div>
@endsection
