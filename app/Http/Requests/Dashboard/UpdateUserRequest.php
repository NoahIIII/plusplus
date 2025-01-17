<?php

namespace App\Http\Requests\Dashboard;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->route('user')->user_id, 'user_id'), 'max:255'],
            'password'=>'nullable|min:8',
            'phone' => ['nullable', 'digits_between:10,15', Rule::unique('users', 'phone')->ignore($this->route('user')->user_id, 'user_id')],
            'user_img'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'status'=>'nullable|boolean',
        ];
    }
    public function failedValidation($validator)
    {
        return ApiResponseTrait::failedValidation($validator, [], null, 422);
    }
}
