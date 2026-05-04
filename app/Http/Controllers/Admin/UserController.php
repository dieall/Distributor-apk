<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private const ROLES = ['admin', 'direktur', 'gudang', 'sales', 'purchasing', 'pelanggan'];

    public function index(Request $request)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');

        $query = User::query()->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = self::ROLES;

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        $roles = self::ROLES;
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'role'      => ['required', Rule::in(self::ROLES)],
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $request->boolean('is_active', true);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "User <strong>{$validated['name']}</strong> berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');
        $roles = self::ROLES;
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'  => 'nullable|string|min:8|confirmed',
            'role'      => ['required', Rule::in(self::ROLES)],
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "User <strong>{$user->name}</strong> berhasil diperbarui.");
    }

    public function toggleActive(User $user)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');

        // Jangan bisa nonaktifkan diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User <strong>{$user->name}</strong> berhasil {$status}.");
    }

    public function destroy(User $user)
    {
        abort_if(auth()->user()->isDirektur(), 403, 'Direktur hanya dapat melihat data.');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $nama = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User <strong>{$nama}</strong> berhasil dihapus.");
    }
}
