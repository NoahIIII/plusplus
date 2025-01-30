<?php

namespace App\Services;

use App\Models\OTP;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;

class OTPService {

    /**
     * send otp to user
     */
    public function sendOTP($phone)
    {
        $user = User::where('phone', $phone)->first();
        $newUser = false;
        if(!$user){
            $user = User::create([
                'phone' => $phone
            ]);
            $newUser = true;
        }
        $code = rand(1000, 9999);
        OTP::create([
            'phone' => $user->phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(5)
        ]);
        return $newUser;
    }

    /**
     * verify otp
     */
    public function verifyOTP($phone,$code)
    {
        $user = User::where('phone', $phone)->first();
        if(!$user){
            return ApiResponseTrait::errorResponse(__('messages.not-found'),404);
        }
        $otp = OTP::where('phone', $user->phone)->where('code', $code)->first();

        // check if the otp exists
        if (!$otp) {
            return ApiResponseTrait::errorResponse(__('messages.invalid-otp'),400);
        }

        // $otp->expires_at is a string, convert it to a Carbon instance
        $expiresAt = Carbon::parse($otp->expires_at);

        // Check if the OTP is expired
        if ($expiresAt->isBefore(now())) {
            return ApiResponseTrait::errorResponse(__('messages.expired-otp'),400);
        }

        // delete the otp
        $otp->delete();

        // verify user phone
        if ($user->phone_verified_at == null) {
            $user->phone_verified_at = now();
        }
        return $user;
    }
}
