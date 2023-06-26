<?php

namespace App\Http\Controllers;

use App\Response\ResponseHandler;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $token = auth()->attempt($request->only(['username', 'password']));
            if (!$token) {
                throw new \Exception("Unauthorized");
            }
            return ResponseHandler::sendTokenResponse($token);
        } catch (\Exception $e) {
            return ResponseHandler::sendErrorResponse($e, 500);
            throw $e;
        }
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return ResponseHandler::sendResponse('User successfully signed out');
    }

    public function refresh(): JsonResponse
    {
        try {
            return ResponseHandler::sendTokenResponse(auth()->refresh());
        } catch (\Exception $e) {
            return ResponseHandler::sendErrorResponse($e);
        }
    }

    public function profile(): JsonResponse
    {
        /** @var User */
        $user = auth()->user();
        $user->load(['createdEvents', 'joinedEvents']);

        return ResponseHandler::sendResponse($user->toArray());
    }
}
