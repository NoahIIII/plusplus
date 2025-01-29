<?php

namespace App\Traits;

use App\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Mockery\Exception\BadMethodCallException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


trait ApiExceptionsTrait
{
    use ApiResponseTrait;

    public static function apiException($e)
    {
        if ($e instanceof NotFoundHttpException) {
            return ApiResponseTrait::successResponse([], __('api.not_found'),  404);
        }

        if ($e instanceof BindingResolutionException) {
            return ApiResponseTrait::successResponse([], __('api.server_error'),  500);
        }

        if ($e instanceof ModelNotFoundException) {
            return ApiResponseTrait::successResponse([], __('api.not_found'),  404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return ApiResponseTrait::successResponse([], __('api.not_allowed_method'),  405);
        }

        if ($e instanceof RouteNotFoundException) {
            return ApiResponseTrait::successResponse([], __('api.not_found_route'),  500);
        }

        if ($e instanceof AuthenticationException) {
            return ApiResponseTrait::successResponse([], __('api.unauthenticated'),  401);
        }

        if ($e instanceof AccessDeniedHttpException) {
            return ApiResponseTrait::successResponse([], __('api.this_action_is_unauthorized'), 403);
        }

        if ($e instanceof BadMethodCallException) {
            return ApiResponseTrait::successResponse([], __('api.not_allowed_method'),  403);
        }
    }
}
