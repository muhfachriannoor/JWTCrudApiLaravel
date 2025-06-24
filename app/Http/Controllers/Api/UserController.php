<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        // Semua endpoint user memerlukan autentikasi JWT
        $this->middleware('auth:api');
    }

    public function index()
    {
        // Hanya superadmin yang dapat melihat data semua user
        if(auth()->user()->role !== 'superadmin') {
            return response()->json(['message' => 'Unauthorized. Only superadmin can view all users.'], 403);
        }

        $users = User::with('hobis')->get();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::with('hobis')->find($id);

        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Hanya superadmin yang dapat melihat user lain. User biasa hanya bisa melihat datanya diri sendiri.   
        if(auth()->user()->role !== 'superadmin' && auth()->user()->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized. You can only view your own profile.'], 403);
        }

        return response()->json($user);
    }

    public function store(Request $request)
    {
        // Hanya superadmin yang dapat membuat user baru
        if(auth()->user()->role !== 'superadmin') {
            return response()->json(['message' => 'Unauthorized. Only superadmin can create users.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:5',
            'role' => 'required|in:superadmin,user',
            'hobis' => 'array',
            'hobis.*.nama_hobi' => 'required|string|max:255',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if($request->has('hobis')) {
            foreach($request->hobis as $hobiData) {
                $user->hobis()->create($hobiData);
            }
        }

        return response()->json(['message' => 'User created successfully', 'user' => $user->load('hobis')], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // superadmin bisa mengupdate user lain dan dirinya. User biasa hanya bisa update datanya sendiri
        if(auth()->user()->role !== 'superadmin' && auth()->user()->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized. You can only update your own profile.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:5', // Password bisa null jika tidak diubah
            'role' => 'sometimes|in:superadmin,user', // Role hanya bisa diubah oleh superadmin
            'hobis' => 'array',
            'hobis.*.nama_hobi' => 'required|string|max:255',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        // Hanya superadmin yang bisa mengubah role user lain
        if(auth()->user()->role === 'superadmin' && $request->has('role')) {
            $user->role = $request->role;
        }
        $user->save();

        // Handle hobi: hapus semua hobi lama lalu tambahkan yang baru
        $user->hobis()->delete(); // Hapus semua hobi lama
        if($request->has('hobis')) {
            foreach ($request->hobis as $hobiData) {
                $user->hobis()->create($hobiData);
            }
        }

        return response()->json(['message' => 'User updated successfully', 'user' => $user->load('hobis')]);
    }

    public function destroy($id)
    {
        if(auth()->user()->role !== 'superadmin') {
            return response()->json(['message' => 'Unauthorized. Only superadmin can delete users.'], 403);
        }

        $user = User::find($id);

        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Jika superadmin tersisa 1 makan tidak akan bisa menghapus datanya
        if(auth()->user()->id === $user->id && $user->role === 'superadmin' && User::where('role', 'superadmin')->count() === 1) {
            return response()->json(['message' => 'Cannot delete the only superadmin account.'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
