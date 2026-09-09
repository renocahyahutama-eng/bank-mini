<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:Administrator,Supervisor,Teller',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'password' => $request->password,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:Administrator,Supervisor,Teller',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        $data = [
            'username' => $request->username,
            'name' => $request->name,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Mengubah status user antara Aktif dan Nonaktif (menggantikan fitur hapus).
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat mengubah status akun sendiri.');
        }

        $newStatus = $user->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $user->update(['status' => $newStatus]);

        return back()->with('success', "Status user '{$user->name}' berhasil diubah menjadi {$newStatus}.");
    }
}
