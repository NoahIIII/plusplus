<?php

namespace App\Http\Requests\Dashboard;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|min:1|max:50',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8',
            'phone'=>'nullable|numeric|unique:users,phone',
            'user_img'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'status'=>'nullable|boolean',
        ];
    }
    public function failedValidation($validator)
    {
        return ApiResponseTrait::failedValidation($validator, [], null, 422);
    }
}
