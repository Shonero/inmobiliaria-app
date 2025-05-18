<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), ['name' => 'required|string|max:255', 'email' => 'required|email|unique:users,email', 'password' => 'required|string|min:6|confirmed',]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 422);
        }
        $user = User::create(['name' => $request->name, 'email' => $request->email, 'password' => Hash::make($request->password),]);
        $token = JWTAuth::fromUser($user);
        return response()->json(['token' => $token]);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
        return response()->json(['token' => $token]);
    }
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Sesión cerrada']);
    }
    public function me()
    {
        return response()->json(auth()->user());
    }
}
