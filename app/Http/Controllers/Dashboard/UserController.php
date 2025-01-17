<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreUserRequest;
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

        // store image
        if($request->hasFile('user_img')){
            $userData['user_img']=StorageService::storeImage($request->file('user_img'), 'users','user-');
        }
        // create new user
        User::create($userData);
        return ApiResponseTrait::apiResponse([],__('messages.added'),[],200);
    }
}
