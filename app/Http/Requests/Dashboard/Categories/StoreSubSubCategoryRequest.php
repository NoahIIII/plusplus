<?php

namespace App\Http\Requests\Dashboard\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubSubCategoryRequest extends FormRequest
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
            'name_en' => 'required|string|min:1|max:50',
            'name_ar' => 'required|string|min:1|max:50',
            'status' => 'nullable|boolean',
            'parent_id' => [
                'required',
                Rule::exists('categories', 'category_id')->where(function ($query) {
                    $query->where('level', 2); // Ensure that the category has level 1
                }),
            ],
        ];
    }
}
