<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hobi;
use Illuminate\Http\Request;

class HobbyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_hobi' => 'required|string|max:255',
        ]);

        Hobi::create([
            'user_id' => $request->user_id,
            'nama_hobi' => $request->nama_hobi,
        ]);

        // Redirect kembali ke halaman edit user dengan pesan sukses
        return redirect()->route('users.edit', $request->user_id)->with('success', 'Hobi berhasil ditambahkan!');
    }

    public function destroy(Hobi $hobi)
    {
        $userId = $hobi->user_id; // Simpan user_id sebelum dihapus
        $hobi->delete();

        // Redirect kembali ke halaman edit user dengan pesan sukses
        return redirect()->route('users.edit', $userId)->with('success', 'Hobi berhasil dihapus!');
    }
}