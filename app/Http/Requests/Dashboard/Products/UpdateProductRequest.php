<?php

namespace App\Http\Requests\Dashboard\Products;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PackageType;
use App\Enums\UnitType;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'name_en' => 'required|string|min:1|max:255',
            'name_ar' => 'required|string|min:1|max:255',
            'description_en' => 'required|string|min:1|max:10000',
            'description_ar' => 'required|string|min:1|max:10000',
            'brand_id' => 'nullable|exists:brands,brand_id',
            'categories' => 'required|array',
            'categories.*' => [
                'required',
                Rule::exists('categories', 'category_id')->where(function ($query) {
                    $query->where('level', 3);
                }),
            ],
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'images' => 'nullable|array',
            'images.*' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'deleted_media_ids'=>'nullable|array',
            'deleted_media_ids.*' => 'required|exists:product_media,id',
            'status' => 'nullable|boolean',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:package_types,id',
            'variants.*.package_type' => ['required', Rule::in(PackageType::values())],
            'variants.*.unit_type' => ['required', Rule::in(UnitType::values())],
            'variants.*.price' => 'nullable|numeric|min:1',
            'variants.*.unit_quantity' => 'nullable|numeric|min:1',
            'variants.*.stock_quantity' => 'nullable|numeric|min:1',
            'primary_image'=>'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }

}
