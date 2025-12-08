<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function __construct()
    {
        // Login & register bisa diakses tanpa token, yang lain butuh auth:api
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'password'     => 'required|string|min:6',
            // optional: kalau mau bisa daftar sebagai admin lewat API
            // 'is_admin'     => 'sometimes|boolean',
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            // default 0 (user biasa). Admin bisa kamu set manual di DB / seeder
            'is_admin'     => $request->input('is_admin', 0),
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'status'  => 'success',
            'message' => 'User created successfully',
            'user'    => [
                'id'           => $user->id,
                'nama_lengkap' => $user->nama_lengkap,
                'email'        => $user->email,
                'is_admin'     => (int) $user->is_admin,
            ],
            'authorisation' => [
                'token' => $token,
                'type'  => 'bearer',
            ],
        ]);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status'  => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        $user = auth('api')->user();

        return response()->json([
            'status' => 'success',
            'user'   => [
                'id'           => $user->id,
                'nama_lengkap' => $user->nama_lengkap,
                'email'        => $user->email,
                'is_admin'     => (int) $user->is_admin,
            ],
            'authorisation' => [
                'token'      => $token,
                'type'       => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ],
        ]);
    }
}
