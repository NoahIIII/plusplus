<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAuthentication
{
    public function handle($request, Closure $next)
    {
        // Rate Limiting
        $key = $request->ip();
        $maxAttempts = env('RATE_LIMIT_ATTEMPTS', 60);
        $decaySeconds = env('RATE_LIMIT_DECAY_SECONDS', 60);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return ApiResponseTrait::errorResponse(
                __('auth.too_many_requests'),
                429,
                ['retry_after' => RateLimiter::availableIn($key)]
            );
        }

        RateLimiter::hit($key, $decaySeconds);

        // JWT Authentication
        $newToken = null;

        try {
            // Try to authenticate the user with the access token
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            try {
                // If token is expired, attempt to refresh it using the refresh token
                $newToken = JWTAuth::refresh(JWTAuth::getToken());

                // Set the refreshed token and re-authenticate the user
                $user = JWTAuth::setToken($newToken)->toUser();
            } catch (TokenExpiredException $e) {
                // If the refresh token expired, force the user to log in again
                return ApiResponseTrait::errorResponse(__('auth.session_expired'), 401, ['force_logout' => true]);
            } catch (JWTException $e) {
                // If token refresh fails for any reason, return an error
                return ApiResponseTrait::errorResponse(__('auth.token_refresh_failed'), 401);
            }
        } catch (TokenInvalidException $e) {
            // If the token is invalid, return an invalid token error
            return ApiResponseTrait::errorResponse(__('auth.invalid_token'), 401);
        } catch (JWTException $e) {
            // If the token is absent or missing, return an error
            return ApiResponseTrait::errorResponse(__('auth.token_absent'), 401);
        }

        if (!$user) {
            return ApiResponseTrait::errorResponse(__('auth.user_not_found'), 401);
        }

        if (!$user->status) {
            return ApiResponseTrait::errorResponse(__('auth.account_deactivated'), 403);
        }

        app()->setLocale($user->locale);

        $response = $next($request);

        if ($newToken) {
            $response->headers->set('Authorization', 'Bearer ' . $newToken);
        }

        return $response;
    }
}
