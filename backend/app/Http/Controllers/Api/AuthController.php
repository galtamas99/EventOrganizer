<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $cred = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        if (! $token = auth('api')->attempt($cred)) {
            throw ValidationException::withMessages(['email' => 'Invalid credentials']);
        }

        if (auth('api')->user()->is_blocked) {
            auth('api')->logout();
            throw ValidationException::withMessages(['email' => 'User is blocked']);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logged out']);
    }

    public function refresh()
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());
        return $this->respondWithToken($newToken);
    }

    protected function respondWithToken(string $token)
    {
        $user = auth('api')->user();

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->values(),
            ],
        ]);
    }
}
