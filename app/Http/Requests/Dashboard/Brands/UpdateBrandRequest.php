<?php

namespace App\Http\Requests\Dashboard\Brands;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
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
            'name_en'=>'required|string|min:1|max:50',
            'name_ar'=>'required|string|min:1|max:50',
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'status'=>'nullable|boolean',
            'business_type_id'=>'required|exists:business_types,id'
        ];
    }
}
