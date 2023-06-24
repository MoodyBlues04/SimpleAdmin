<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function signUp(Request $request)
    {
        $request->validate([ // TODO custom request objects with rules method
            'login' => 'required|string|unique:users',
            'name' => 'required|string',
            'surname' => 'required|string',
            'password' => 'required|string|min:6',
            'birthday' => 'nullable|date',
        ]);

        $this->create($request);

        return response()->json(['ok' => true, 'message' => 'Signed up']);
    }

    public function logIn(Request $request) // TODO error handling (try-catch)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return response()->json(['ok' => true, 'message' => 'Logged in']); // TODO handler
        }

        return response()->json(['ok' => false, 'message' => 'Invalid credentials']);
    }

    public function logOut()
    {
        Auth::logout();

        return response()->json(['ok' => true, 'message' => 'Logged out']);
    }

    private function create(Request $request)
    {
        return User::create([ // TODO factory | repository
            'login' => $request->input('login'),
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'password' => Hash::make($request->input('password')),
            'birthday' => $request->input('birthday') ?? null,
        ]);
    }
}
