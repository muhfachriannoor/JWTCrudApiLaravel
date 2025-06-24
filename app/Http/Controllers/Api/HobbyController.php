<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hobi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HobbyController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index($userId)
    {
        $user = User::find($userId);

        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        if(auth()->user()->role !== 'superadmin' && auth()->user()->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized. You can only view your own hobi.'], 403);
        }
        return response()->json($user->hobis);
    }

    public function store(Request $request, $userId)
    {
        $user = User::find($userId);

        if(!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Superadmin bisa menambahkan hobi ke user lain. User biasa hanya ke dirinya sendiri.
        if(auth()->user()->role !== 'superadmin' && auth()->user()->id !== $user->id) {
            return response()->json(['message' => 'Unauthorized. You can only add hobi to your own profile.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'nama_hobi' => 'required|string|max:255',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $hobi = $user->hobis()->create($validator->validated());

        return response()->json(['message' => 'Hobi added successfully', 'hobi' => $hobi], 201);
    }

    public function destroy($hobiId)
    {
        $hobi = Hobi::find($hobiId);
        if (!$hobi) {
            return response()->json(['message' => 'Hobi not found'], 404);
        }

        // Superadmin bisa menghapus hobi siapapun. User biasa hanya bisa menghapus hobi miliknya.
        if(auth()->user()->role !== 'superadmin' && auth()->user()->id !== $hobi->user_id) {
            return response()->json(['message' => 'Unauthorized. You can only delete your own hobi.'], 403);
        }

        $hobi->delete();
        return response()->json(['message' => 'Hobi deleted successfully'], 200);
    }
}
