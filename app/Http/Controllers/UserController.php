<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Response\ResponseHandler;
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
        try {

            $user->load('createdEvents');
            return ResponseHandler::sendResponse($user->toArray());
        } catch (\Exception $e) {
            return ResponseHandler::sendErrorResponse($e);
        }
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        try {
            $this->createUser($request);

            return ResponseHandler::sendResponse([
                'access_token' => $this->getAccessToken($request)
            ], 201);
        } catch (\Exception $e) {
            return ResponseHandler::sendErrorResponse($e);
        }
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

    private function getAccessToken(Request $request): string
    {
        return auth()->attempt($request->only(['username', 'password']));
    }
}
