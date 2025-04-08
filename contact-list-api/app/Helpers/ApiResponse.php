<?php

namespace App\Helpers;

class ApiResponse
{
    /**
     * Success response method.
     *
     * @param array $data The response data
     * @param string $message The success message
     * @param int $status The HTTP status code
     * @return JsonResponse
     */
    public static function success($data = [], $message = 'Success', $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'code' => $status,
        ], $status);
    }

    /**
     * Error response method.
     *
     * @param string $message The error message
     * @param int $status The HTTP status code
     * @param array $errors Additional error details
     * @return JsonResponse
     */
    public static function error($message = 'Something went wrong', $status = 500, $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'code' => $status,
        ], $status);
    }
}
