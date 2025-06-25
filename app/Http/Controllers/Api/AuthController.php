<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|confirmed|min:5',
            ]);

            if($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => Hash::make($request->password), 'role' => 'user']
            ));

            return response()->json([
                'message' => 'User succesfully registered',
                'user' => $user
            ], 201);
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function login(Request $request)
    {

        try {
             $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:5',
            ]);

            if($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if(!$token = auth()->guard('api')->attempt($validator->validated())) {
                return response()->json([
                    'message' => 'Email atau Password Anda salah'
                ], 401);
            }

            return $this->respondWithToken($token);
        } catch (JWTException  $th) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    public function me()
    {
        try {
            return response()->json(auth()->user());
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function refresh()
    {
        try {
            return $this->respondWithToken(auth()->refresh());
        } catch (JWTException $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    protected function respondWithToken($token)
    {
        $user = auth()->guard('api')->user();
        $expiresIn = auth('api')->factory()->getTTL() * 60;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiresIn,
            'user' => $user
        ]);
    }
}
