<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiResponseTrait
{
    public static function apiResponse($data = [], $message = '', $error = [], $status = 200)
    {
        $response = response()
            ->json([
                'data' => $data,
                'message' => $message,
                'error' => $error,
                'status' => $status
            ], $status);
        $response->header('Cache-Control', 'public, max-age=3600');
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
