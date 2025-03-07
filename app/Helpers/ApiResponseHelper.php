<?php

namespace App\Helpers;

class ApiResponseHelper
{
    /**
     * Standardized API Response
     *
     * @param  mixed  $data  The response data
     * @param  string  $message  The message to return
     * @param  int  $status  The HTTP status code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendResponse($data, $message = 'Success', $status = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Standardized API Error Response
     *
     * @param  string  $errorMessage  The error message
     * @param  int  $status  The HTTP status code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function sendError($errorMessage, $status = 400)
    {
        return response()->json([
            'message' => $errorMessage,
            'data' => null,
        ], $status);
    }
}
