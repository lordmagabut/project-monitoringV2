<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Halaman Daftar User
    public function index()
    {
        if (auth()->user()->akses_user_manager != 1) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $users = User::all();

        return view('user.index', compact('users'));
    }

    // Form Edit Permission
    public function editPermission($id)
    {
        if (auth()->user()->akses_user_manager != 1) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $user = User::findOrFail($id);
        return view('user.edit_permission', compact('user'));
    }

    // Update Permission
    public function updatePermission(Request $request, $id)
    {
        if (auth()->user()->akses_user_manager != 1) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $user = User::findOrFail($id);

        $user->update([
            'akses_perusahaan' => $request->akses_perusahaan ?? 0,
            'buat_perusahaan' => $request->buat_perusahaan ?? 0,
            'edit_perusahaan' => $request->edit_perusahaan ?? 0,
            'hapus_perusahaan' => $request->hapus_perusahaan ?? 0,
            'akses_pemberikerja' => $request->akses_pemberikerja ?? 0,
            'akses_proyek' => $request->akses_proyek ?? 0,
            'akses_barang' => $request->akses_barang ?? 0,
            'akses_coa' => $request->akses_coa ?? 0,
            'akses_po' => $request->akses_po ?? 0,
            'buat_po' => $request->buat_po ?? 0,
            'edit_po' => $request->edit_po ?? 0,
            'hapus_po' => $request->hapus_po ?? 0,
            'akses_user_manager' => $request->akses_user_manager ?? 0,
        ]);

        return redirect()->route('user.index')->with('success', 'Permission berhasil diperbarui.');
    }

    // Form Tambah User Baru
public function create()
{
    if (auth()->user()->akses_user_manager != 1) {
        abort(403, 'Anda tidak memiliki akses.');
    }

    return view('user.create');
}

// Simpan User Baru
public function store(Request $request)
{
    if (auth()->user()->akses_user_manager != 1) {
        abort(403, 'Anda tidak memiliki akses.');
    }

    $request->validate([
        'username' => 'required|unique:users,username',
        'password' => 'required|min:4',
    ]);

    \App\Models\User::create([
        'username' => $request->username,
        'password' => bcrypt($request->password),
        // Default permission tidak aktif
        'akses_perusahaan' => 0,
        'buat_perusahaan' => 0,
        'akses_pemberikerja' => 0,
        'akses_proyek' => 0,
        'akses_barang' => 0,
        'akses_coa' => 0,
        'akses_po' => 0,
        'buat_po' => 0,
        'edit_po' => 0,
        'hapus_po' => 0,
        'akses_user_manager' => 0,
    ]);

    return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
}

// Form Reset Password
public function showResetPasswordForm($id)
{
    if (auth()->user()->akses_user_manager != 1) {
        abort(403, 'Anda tidak memiliki akses.');
    }

    $user = \App\Models\User::findOrFail($id);
    return view('user.reset_password', compact('user'));
}

// Proses Reset Password
public function resetPassword(Request $request, $id)
{
    if (auth()->user()->akses_user_manager != 1) {
        abort(403, 'Anda tidak memiliki akses.');
    }

    $request->validate([
        'password' => 'required|min:4|confirmed',
    ]);

    $user = \App\Models\User::findOrFail($id);
    $user->update([
        'password' => bcrypt($request->password)
    ]);

    return redirect()->route('user.index')->with('success', 'Password berhasil direset.');
}

// Hapus User
public function destroy($id)
{
    if (auth()->user()->akses_user_manager != 1) {
        abort(403, 'Anda tidak memiliki akses.');
    }

    $user = \App\Models\User::findOrFail($id);

    // Proteksi agar user tidak bisa menghapus dirinya sendiri
    if (auth()->id() == $user->id) {
        return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }

    $user->delete();

    return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
}



}
