<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a standardized success JSON response.
     *
     * @param  array<string, mixed>  $extras
     */
    protected function successResponse(mixed $data, string $message = '', int $code = 200, array $extras = []): JsonResponse
    {
        $response = ['success' => true];

        if ($message !== '') {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        if (! empty($extras)) {
            $response = array_merge($response, $extras);
        }

        return response()->json($response, $code);
    }

    /**
     * Return a standardized error JSON response.
     *
     * @param  array<string, mixed>  $errors
     */
    protected function errorResponse(string $message, int $code = 400, array $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (! empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
