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

        if ($token = auth('api')->attempt($credentials)) {
            return $this->respondWithToken($token, 'api');
        }

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
        $limit = $request->input('is_admin') ? 'admins' : 'users';
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => "required|string|email|max:255|unique:$limit",
            'password'     => 'required|string|min:6',
        ]);

        if ($request->input('is_admin')) {
            $admin = \App\Models\Admin::create([
                'nama'     => $request->nama_lengkap,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = auth('admin')->login($admin);

            return response()->json([
                'status'  => 'success',
                'message' => 'Admin created successfully',
                'user'    => [
                    'id'           => $admin->id,
                    'nama_lengkap' => $admin->nama,
                    'email'        => $admin->email,
                    'is_admin'     => 1,
                ],
                'authorisation' => [
                    'token' => $token,
                    'type'  => 'bearer',
                ],
            ]);
        }

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'is_admin'     => 0,
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'status'  => 'success',
            'message' => 'User created successfully',
            'user'    => [
                'id'           => $user->id,
                'nama_lengkap' => $user->nama_lengkap,
                'email'        => $user->email,
                'is_admin'     => 0,
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
        $guard = 'api';
        if (Auth::guard('admin')->check()) {
            $guard = 'admin';
        }
        return $this->respondWithToken(Auth::refresh(), $guard);
    }

    protected function respondWithToken($token, $guard = null)
    {
        
        $currentGuard = $guard ?: 'api';
        $user = auth($currentGuard)->user();

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

