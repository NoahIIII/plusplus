<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\Addresses\AddressRequest;
use App\Http\Requests\Public\Auth\UpdateAccountRequest;
use App\Models\Address;
use App\Models\BusinessType;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $userId;

    public function __construct()
    {
        // there's a middleware to make sure the user is authenticated, make sure to apply it for all methods in this controller,
        // i don't like to apply the middlewares in the controller tho.
        $this->userId = auth('users')->user()->user_id;
    }

    /**
     * Update User Account Details
     */
    public function updateAccountDetails(UpdateAccountRequest $request)
    {
        $user = User::find($this->userId);
        $user->update($request->validated());
        return ApiResponseTrait::successResponse([], __('messages.updated'));
    }
    /**
     * Update Current User Business Type
     */
    public function updateBusinessType(Request $request)
    {
        // validate business type
        $businessType = BusinessType::find($request->business_type_id);
        if (!$businessType) return ApiResponseTrait::errorResponse(__('messages.not-found'), 404);
        // get the user
        $user = User::find($this->userId);
        if (!$user) return ApiResponseTrait::errorResponse(__('messages.not-found'), 403);
        // update business type
        $user->business_type_id = $request->business_type_id;
        $user->save();
        return ApiResponseTrait::successResponse([], __('messages.updated'));
    }
    /**
     * Update User  Addresses
     * @param AddressRequest $request
     */
    public function updateAddress(AddressRequest $request)
    {
        $address = Address::firstOrCreate([
            'user_id' => $this->userId,
            'type' => $request->type,
        ]);
        $address->update($request->validated());
        return ApiResponseTrait::successResponse([], __('messages.updated'));
    }
}
