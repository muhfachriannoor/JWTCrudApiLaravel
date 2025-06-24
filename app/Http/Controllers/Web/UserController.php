<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Hanya superadmin yang dapat melihat data semua user
        if(Auth::user()->role !== 'superadmin') {
            return redirect('/home')->with('error', 'Anda tidak memiliki akses untuk melihat daftar user.');
        }

        $users = User::with('hobis')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        if(Auth::user()->role !== 'superadmin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat user baru.');
        }

        return view('users.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        if(Auth::user()->role !== 'superadmin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat user baru.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:superadmin,user',
            'hobis' => 'nullable|array',
            'hobis.*' => 'nullable|string|max:255', // Validasi untuk setiap string hobi
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if($request->has('hobis')) {
            foreach ($request->hobis as $namaHobi) {
                if (!empty($namaHobi)) {
                     $user->hobis()->create(['nama_hobi' => $namaHobi]);
                }
            }
        }

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        // Superadmin bisa mengedit user lain. User biasa hanya bisa mengedit dirinya sendiri.
        if(Auth::user()->role !== 'superadmin' && Auth::user()->id !== $user->id) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit user ini.');
        }
        $user->load('hobis'); // Muat relasi hobi
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Superadmin bisa update user lain. User biasa hanya bisa update dirinya sendiri.
        if(Auth::user()->role !== 'superadmin' && Auth::user()->id !== $user->id) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit user ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed', // Password opsional, dan harus dikonfirmasi
            'role' => 'nullable|in:superadmin,user', // Role opsional, hanya superadmin yang bisa ubah
            'hobis' => 'nullable|array',
            'hobis.*' => 'nullable|string|max:255',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) { // Hanya update password jika diisi
            $user->password = Hash::make($request->password);
        }

        if (Auth::user()->role === 'superadmin' && $request->filled('role')) { // Hanya superadmin bisa ubah role
            $user->role = $request->role;
        }
        $user->save();

        // Update hobi: hapus semua hobi lama lalu tambahkan yang baru
        $user->hobis()->delete();
        if ($request->has('hobis')) {
            foreach ($request->hobis as $namaHobi) {
                if (!empty($namaHobi)) {
                    $user->hobis()->create(['nama_hobi' => $namaHobi]);
                }
            }
        }

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'superadmin') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses untuk menghapus user.');
        }

        // Jika superadmin tersisa 1 makan tidak akan bisa menghapus datanya
        if (Auth::user()->id === $user->id && $user->role === 'superadmin' && User::where('role', 'superadmin')->count() === 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus satu-satunya akun superadmin.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}
