<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StaffUsers\StoreStaffUserRequest;
use App\Http\Requests\Dashboard\StaffUsers\UpdateStaffUserRequest;
use App\Models\StaffUser;
use App\Pipelines\Filters\AdminFilter;
use App\Services\StorageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Pipeline\Pipeline;


class StaffUserController extends Controller
{
    /**
     * get all staff users
     */
    public function index()
    {
        $staffUsers = app(Pipeline::class)
        ->send(StaffUser::query())
        ->through([
            AdminFilter::class,
        ])
        ->thenReturn()
        ->paginate(20);
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
        unset($staffUserData['permissions'], $staffUserData['super_admin']);
        // store the staff user
        $staffUser = StaffUser::create($staffUserData);
        // Sync the permissions with the staff user
        if ($request->permissions) {
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name');
            $staffUser->syncPermissions($permissions);
        }
        // sync the roles
        if ($request->super_admin) {
            $staffUser->assignRole('super-admin');
        }
        return ApiResponseTrait::apiResponse([], __('messages.added'), [], 200);
    }

    /**
     * view the edit form
     * @param mixed $staffUserId
     */
    public function edit($staffUserId)
    {
        $staffUser = StaffUser::findOrFail($staffUserId);
        $staffUserPermissions = $staffUser->permissions()->pluck('id')->toArray();
        $permissions = Permission::select('name', 'id')->get();
        return view('admins.edit', get_defined_vars());
    }

    /**
     * update staff user
     * @param UpdateStaffUserRequest $request
     * @param StaffUser $staffUser
     */
    public function update(UpdateStaffUserRequest $request, StaffUser $staffUser)
    {
        // get data from request
        $staffUserData = $request->validated();
        $staffUserData['status'] = $request->status ?? 0;
        if ($request->filled('password')) {
            $staffUserData['password'] = Hash::make($request->password);
        } else {
            unset($staffUserData['password']);
        }
        // handle the uploaded image
        $staffUserImg = $request->hasFile('staff_user_img')
            ? StorageService::storeImage($request->file('staff_user_img'), 'admins', 'staff_user-')
            : null;
        $staffUserData['staff_user_img'] = $staffUserImg;
        // delete old image
        if ($staffUser->staff_user_img) StorageService::deleteImage($staffUser->staff_user_img);
        // unset the permissions array
        unset($staffUserData['permissions'], $staffUserData['super_admin']);
        // update the staff user
        $staffUser->update($staffUserData);
        // Sync the permissions with the staff user
        $permissionIds = $request->permissions ?? [];
        $permissions = Permission::whereIn('id', $permissionIds)->pluck('name');
        $staffUser->syncPermissions($permissions);
        // sync the roles
        if ($request->super_admin) {
            $staffUser->assignRole('super-admin');
        } else {
            $staffUser->removeRole('super-admin');
        }
        return ApiResponseTrait::apiResponse([], __('messages.updated'), [], 200);
    }

    /**
     * delete admin
     */
    public function destroy(StaffUser $staffUser)
    {
        // delete the user image
        $staffUser->user_img ? StorageService::deleteImage($staffUser->staff_user_img) : null;
        $staffUser->delete();
        return back()->with('Success', __('messages.deleted'));
    }
}
