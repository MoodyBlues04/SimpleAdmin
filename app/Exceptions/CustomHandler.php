<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomHandler
{
    /**
     * Sends bad http response based on the given Exception
     * 
     * @param \Exception $exception
     * 
     * @return JsonResponse
     */
    public static function sendResponse(\Exception $exception): JsonResponse
    {
        return response()->json([
            'message' => $exception->getMessage(),
            'path' => $exception->getFile(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
