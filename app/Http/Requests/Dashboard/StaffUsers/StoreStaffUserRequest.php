<?php

namespace App\Http\Requests\Dashboard\StaffUsers;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
            'status' => 'nullable|boolean',
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
            'staff_user_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'super_admin' => 'nullable|boolean',
        ];
    }
}
