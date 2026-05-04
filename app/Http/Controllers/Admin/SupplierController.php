<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query()->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%{$s}%");
        }

        $suppliers = $query->paginate(15)->withQueryString();

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Supplier::create($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $supplier->update($data);

        return redirect()->route('admin.suppliers.index')->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        if (Pembelian::where('supplier_id', $supplier->id)->exists()) {
            return back()->with('error', 'Supplier tidak dapat dihapus karena masih digunakan pada Purchase Order.');
        }

        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
