<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    public function index(): JsonResponse
    {
        return response()->json(User::all());
    }

    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    public function store(Request $request): JsonResponse
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

    private function createUser(Request $request): User|bool
    {
        return User::create([ // TODO factory | repository
            'username' => $request->username,
            'name' => $request->name,
            'surname' => $request->surname,
            'password' => Hash::make($request->password),
            'birthday' => $request->birthday ?? null,
        ]);
    }
}
