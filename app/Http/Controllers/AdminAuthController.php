<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        if (! $token = auth('admin')->attempt($credentials)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('admin')->user());
    }

    public function logout()
    {
        auth('admin')->logout();
        return response()->json(['status' => 'success', 'message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('admin')->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'user' => auth('admin')->user(),
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
                'expires_in' => auth('admin')->factory()->getTTL() * 60
            ]
        ]);
    }
}
