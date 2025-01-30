<?php

namespace App\Http\Requests\Dashboard;

use App\Rules\ProductExistsInBusinessType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountRequest extends FormRequest
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
            'business_type_id'=>'required|exists:business_types,id',
            'type'=>'required|in:fixed,percentage,buy_one_get_one',
            'value'=>'required|numeric|min:1|max:99',
            'start_date'=>'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'product_ids'=>'required|array',
            'product_ids.*' => [
                'required',
                'numeric',
                new ProductExistsInBusinessType($this->business_type_id),
            ],
        ];
    }
}
