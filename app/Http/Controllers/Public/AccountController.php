<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\Auth\UpdateAccountRequest;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $userId;

    public function __construct()
    {
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
}
