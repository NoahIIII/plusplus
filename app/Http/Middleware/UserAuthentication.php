<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
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
        $user = null;
        $newToken = null;
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
        try {
            // Authenticate user with the access token
            $user = JWTAuth::parseToken()->authenticate();
            Auth::payload(); // This will trigger TokenExpiredException if token is expired
        } catch (TokenExpiredException $e) {
            try {
                // Refresh the token
                $newToken = JWTAuth::refresh(JWTAuth::getToken());
                $user = JWTAuth::setToken($newToken)->toUser();
            } catch (TokenExpiredException $e) {
                return ApiResponseTrait::errorResponse(__('auth.session_expired'), 401, ['try_manual_refresh' => true]);
            } catch (JWTException $e) {
                return ApiResponseTrait::errorResponse(__('auth.token_refresh_failed'), 401);
            }
        } catch (TokenInvalidException $e) {
            return ApiResponseTrait::errorResponse(__('auth.invalid_token'), 401);
        } catch (JWTException $e) {
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
            $content = json_decode($response->getContent(), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $content = [];
            }

            return $response->header('Authorization', 'Bearer ' . $newToken)
                ->setContent(array_merge($content, ['new_token' => $newToken]));
        }

        return $response;
    }
}
