<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiTokenController extends Controller
{
    public function refresh(Request $request) // TODO to custom request & token expiration
    {
        $token = \Illuminate\Support\Str::random(60);

        $user = $this->getUser($request);
        $user->api_token = hash('sha256', $token);
        $user->save();

        return response()->json(['ok' => true, 'token' => $token]);
    }

    private function getUser(Request $request): User
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        $user = User::query()->where('login', $login)->first();

        if (empty($user)) {
            throw new \InvalidArgumentException("Invalid login: {$login}");
        }
        if (!Hash::check($password, $user->password)) {
            throw new \InvalidArgumentException("Invalid password: {$password}");
        }
        return $user;
    }
}
