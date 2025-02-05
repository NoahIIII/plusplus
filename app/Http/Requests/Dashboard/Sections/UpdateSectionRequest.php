<?php

namespace App\Http\Requests\Dashboard\Sections;

use App\Rules\ProductExistsInBusinessType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSectionRequest extends FormRequest
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
            'first_color'=>'required|string',
            'second_color'=>'required|string',
            'business_type_id'=>'required|numeric|exists:business_types,id',
            'status'=>'required|boolean',
            'product_ids'=>'required|array',
            'product_ids.*' => [
                'required',
                'numeric',
                new ProductExistsInBusinessType($this->business_type_id),
            ],
        ];
    }
}
