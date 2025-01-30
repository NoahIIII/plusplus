<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\Auth\SendOTPRequest;
use App\Http\Requests\Public\Auth\VerifyOTPRequest;
use App\Http\Requests\Public\RegisterRequest;
use App\Rules\PhoneNumber;
use App\Services\OTPService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function __construct(private OTPService $oTPService) {}
    /**
     * Send Otp for the user phone number to login / register
     *@param SendOTPRequest $request
     */
    public function sendOTP(SendOTPRequest $request)
    {
        $phone = $request->validated()['phone'];
        // send the otp
        $isNewUser = $this->oTPService->sendOTP($phone);
        // return the response if any
        if ($isNewUser instanceof JsonResponse) return $isNewUser;
        return ApiResponseTrait::successResponse(['is_new_user' => $isNewUser], __('messages.otp-sent'));
    }
    /**
     * verify otp for the user phone number to login / register
     * @param VerifyOTPRequest $request
     */
    public function verifyOTP(VerifyOTPRequest $request)
    {
        // Rate Limiting
        $key = $request->ip();
        $maxAttempts = 5;
        $decaySeconds = env('RATE_LIMIT_DECAY_SECONDS', 60);
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return ApiResponseTrait::errorResponse(
                __('auth.too_many_requests'),
                429,
                ['retry_after' => RateLimiter::availableIn($key)]
            );
        }
        RateLimiter::hit($key, $decaySeconds);
        // get request data
        $phone = $request->validated()['phone'];
        $code = $request->validated()['code'];
        // verify the otp
        $user = $this->oTPService->verifyOTP($phone, $code);
        if ($user instanceof JsonResponse) return $user;
        $token = auth('users')->login($user);
        return ApiResponseTrait::successResponse(['token' => $token], __('messages.otp-verified'));
    }
}
