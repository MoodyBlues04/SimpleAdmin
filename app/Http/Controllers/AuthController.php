<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request): JsonResponse // TODO another controller ? 
    {
        $request->validate([ // TODO custom request objects with rules method
            'username' => 'required|string|unique:users',
            'name' => 'required|string',
            'surname' => 'required|string',
            'password' => 'required|string|min:6',
            'birthday' => 'nullable|date',
        ]);

        $this->createUser($request);

        return response()->json(['ok' => true, 'result' => ['message' => 'Signed up']], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $token = auth('api')->attempt($request->only(['username', 'password']));
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->tokenResponse($token);
    }

    public function logout(): JsonResponse
    {
        auth('api')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh(): JsonResponse
    {
        return $this->tokenResponse(auth('api')->refresh());
    }

    public function userProfile(): JsonResponse
    {
        return response()->json(auth('api')->user());
    }

    private function tokenResponse(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }

    private function createUser(Request $request): User|bool
    {
        return User::create([ // TODO factory | repository
            'username' => $request->input('username'),
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'password' => Hash::make($request->input('password')),
            'birthday' => $request->input('birthday') ?? null,
        ]);
    }
}
