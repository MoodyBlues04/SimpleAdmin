<?php

namespace App\Response;

use Illuminate\Http\JsonResponse;

class ResponseHandler
{
    public static function sendResponse(array|string $response, int $code = 200, array $headers = []): JsonResponse
    {
        if (is_string($response)) {
            $response = ['message' => $response];
        }
        return response()->json($response, $code, $headers);
    }

    public static function sendErrorResponse(\Exception $e, $code =  500): JsonResponse
    {
        return self::sendResponse($e->getMessage(), $code);
    }

    public static function sendTokenResponse(string $token): JsonResponse
    {
        $response = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ];

        return self::sendResponse($response);
    }
}
