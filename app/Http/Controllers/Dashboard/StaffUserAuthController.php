<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseHandler;
use App\Models\StaffUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffUserAuthController extends Controller
{
    /**
     * Login for Staff Users (Admins).
     *
     * @param Request $request
     */
    public function login(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // If validation fails, redirect back with the first error message
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Attempt to retrieve the user by email
        $staffUser = StaffUser::where('email', $request->email)->first();

        // If no user is found, return an error
        if (!$staffUser) {
            return redirect()->back()->with('error', __('auth.failed'));
        }

        // Check if the provided password matches the stored hashed password
        if (!Hash::check($request->password, $staffUser->password)) {
            return redirect()->back()->with('error', __('auth.failed'));
        }

        // Check if the account is active
        if (!$staffUser->status) {
            return redirect()->back()->with('error', __('messages.deactivated-account'));
        }

        // Log in the user using the web guard and redirect to the dashboard
        Auth::guard('staff_users')->login($staffUser);

        // Redirect to the home route after a successful login
        return redirect()->route('dashboard.index');
    }


    /**
     * Logout for Staff Users (Admins).
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        // Logout the current user
        Auth::guard('staff_users')->logout();

        // Invalidate the session to prevent reuse
        $request->session()->invalidate();

        // Regenerate the CSRF token to prevent CSRF attacks
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect()->route('login');
    }
}
