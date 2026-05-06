<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hanya Admin dan Super Admin yang bisa mengakses ini
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $users = User::orderBy('name', 'asc')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,user'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        // ATURAN KETAT: Admin tidak bisa mengedit data Admin lain (kecuali Super Admin)
        if (!auth()->user()->isSuperAdmin() && $user->isAdmin() && auth()->id() !== $user->id) {
            return back()->with('error', 'Anda tidak memiliki otoritas untuk mengedit data Admin lain.');
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,user'],
        ];

        $isChangingPassword = $request->filled('password');
        if ($isChangingPassword) {
            $rules['password'] = [Rules\Password::defaults()];
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($isChangingPassword) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        // Jangan hapus diri sendiri atau superadmin
        if ($user->id === auth()->id() || $user->isSuperAdmin()) {
            return back()->with('error', 'User ini tidak dapat dihapus.');
        }

        // ATURAN KETAT: Admin tidak bisa menghapus Admin lain
        if (!auth()->user()->isSuperAdmin() && $user->isAdmin()) {
            return back()->with('error', 'Admin tidak diperbolehkan menghapus Admin lain.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
