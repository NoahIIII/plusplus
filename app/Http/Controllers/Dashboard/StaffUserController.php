<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreStaffUserRequest;
use App\Models\StaffUser;
use App\Services\StorageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class StaffUserController extends Controller
{
    /**
     * get all staff users
     */
    public function index()
    {
        $staffUsers = StaffUser::paginate(10);
        return view('admins.index', compact('staffUsers'));
    }

    /**
     * view the store form
     */
    public function create()
    {
        $permissions = Permission::select('name', 'id')->get();
        return view('admins.create', compact('permissions'));
    }

    /**
     * store staff user
     * @param StoreStaffUserRequest $request
     */
    public function store(StoreStaffUserRequest $request)
    {
        // get data from request
        $staffUserData = $request->validated();
        $staffUserData['password'] = Hash::make($staffUserData['password']);
        $staffUserData['status'] = $request->status ?? 0;
        // handle the uploaded image
        $staffUserImg = $request->hasFile('staff_user_img')
            ? StorageService::storeImage($request->file('staff_user_img'), 'admins', 'staff_user-')
            : null;
        $staffUserData['staff_user_img'] = $staffUserImg;
        // unset the permissions array
        unset($staffUserData['permissions']);
        // store the staff user
        $staffUser = StaffUser::create($staffUserData);
        // Sync the permissions with the staff user
        if ($request->permissions) {
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name');
            $staffUser->syncPermissions($permissions);
        }
        return ApiResponseTrait::apiResponse([], __('messages.added'), [], 200);
    }

    /**
     * delete admin
     */
    public function destroy(StaffUser $staffUser)
    {
        // delete the user image
        $staffUser->user_img ? StorageService::deleteImage($staffUser->staff_user_img) : null;
        $staffUser->delete();
        return back()->with('Success',__('messages.deleted'));
    }
}
