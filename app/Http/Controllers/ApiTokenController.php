<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    public function refresh(Request $request)
    {
        $token = \Illuminate\Support\Str::random(60);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return response()->json(['ok' => true, 'token' => $token]);
    }
}
