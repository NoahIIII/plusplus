<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiResponseTrait
{
    public static function successResponse($data = [], $message = null, $status = 200)
    {
        $response = response()
            ->json([
                'data' => $data,
                'message' => $message,
                'status' => $status
            ], $status);
        $response->header('Cache-Control', 'public, max-age=3600');
        return $response;
    }

    public static function errorResponse($message = null, $status = 400, $details = [], $code = null)
    {
        // Use HTTP status code as the default code if none is provided
        $errorCode = $code ?? (string)$status;

        $response = response()
            ->json([
                'error' => [
                    'code' => $errorCode,
                    'message' => $message,
                    'details' => $details,
                ],
                'status' => $status
            ], $status);

        $response->header('Cache-Control', 'no-store');
        return $response;
    }


    public static function failedValidation($validator, $data = [], $message = '', $status)
    {
        $errors = $validator->errors()->toArray();
        $response = [
            'data' => $data,
            'message' => $message,
            'errors' => $errors,
            'status' => $status
        ];
        throw new HttpResponseException(response()->json($response, $status));
    }
}
