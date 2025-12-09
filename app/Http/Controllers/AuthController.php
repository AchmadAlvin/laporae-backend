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
        $this->middleware('auth:api,admin', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Try login as User
        if ($token = auth('api')->attempt($credentials)) {
            return $this->respondWithToken($token, 'api');
        }

        // Try login as Admin
        if ($token = auth('admin')->attempt($credentials)) {
            return $this->respondWithToken($token, 'admin');
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Unauthorized',
        ], 401);
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
        return response()->json(Auth::user());
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status'  => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        // Refresh token for the current guard
        return $this->respondWithToken(Auth::refresh(), Auth::guard()->name);
    }

    protected function respondWithToken($token, $guard = null)
    {
        // If guard is not specified, try to determine from auth defaults or current state
        // But login() calls this explicitly. refresh() calls it with name.
        
        $currentGuard = $guard ?: 'api';
        $user = auth($currentGuard)->user();

        // Handle inconsistent model attributes between User and Admin
        $name = $user->nama_lengkap ?? $user->nama ?? 'Unknown';
        $isAdmin = $user->is_admin ?? ($currentGuard === 'admin' ? 1 : 0);

        return response()->json([
            'status' => 'success',
            'user'   => [
                'id'           => $user->id,
                'nama_lengkap' => $name,
                'email'        => $user->email,
                'is_admin'     => (int) $isAdmin,
            ],
            'authorisation' => [
                'token'      => $token,
                'type'       => 'bearer',
                'expires_in' => auth($currentGuard)->factory()->getTTL() * 60,
            ],
        ]);
    }
}

