<?php

namespace App\Http\Requests\Dashboard\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateCategoryRequest extends FormRequest
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
                'nullable',
                'exists:categories,category_id',
            ],
            'business_type_id' => [
                'nullable',
                'exists:business_types,id'
            ],
            'level' => [
                'nullable',
                Rule::in([1, 2, 3])
            ]
        ];
    }
}
