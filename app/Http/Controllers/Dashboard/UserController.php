<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreUserRequest;
use App\Http\Requests\Dashboard\UpdateUserRequest;
use App\Models\User;
use App\Services\StorageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * get all the users
     *
     * @return void
     */
    public function index(){
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }
    /**
     * view the store user form
     *
     */
    public function create(){
        return view('users.create');
    }
    /**
     * Add new User
     */
    public function store(StoreUserRequest $request){
        // get data from request
        $userData=$request->validated();
        $userData['status'] =  $request->status ?? 0;
        // store image
        if($request->hasFile('user_img')){
            $userData['user_img']=StorageService::storeImage($request->file('user_img'), 'users','user-');
        }
        // create new user
        User::create($userData);
        return ApiResponseTrait::apiResponse([],__('messages.added'),[],200);
    }

    /**
     * view the user details
     * @param mixed $userId
     */
    public function show($userId)
    {

    }

    /**
     * return update user form
     * @param mixed $userId
     */
    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        return view('users.edit', compact('user'));
    }

    /**
     * update user data
     * @param UpdateUserRequest $request
     * @param User $user
     */

    public function update(UpdateUserRequest $request, User $user)
    {
        // get data from request
        $userData=$request->validated();
        if($request->password == null) unset($userData['password']);
        if($request->hasFile('user_img')){
            $user->user_img ? StorageService::deleteImage($user->user_img) : null;
            $userData['user_img']=StorageService::storeImage($request->file('user_img'), 'users','user-');
        }
        $userData['status'] = $request->status ?? 0;
        $user->update($userData);
        return ApiResponseTrait::apiResponse([],__('messages.updated'),[],200);
    }

    /**
     * delete user
     */
    public function destroy(User $user)
    {
        // delete the user image
        $user->user_img ? StorageService::deleteImage($user->user_img) : null;
        $user->delete();
        return back()->with('Success',__('messages.deleted'));
    }
}
