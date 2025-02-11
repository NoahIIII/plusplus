<?php

namespace App\Http\Requests\Public\Addresses;

use App\Traits\ApiResponseTrait;
use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'type' => $this->input('type', 'default'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address_lat' => 'required|min:-90|max:90',
            'address_long' => 'required|min:-180|max:180',
            'type'=>'nullable|in:default,home,essential,work,other',
            'department'=>'nullable|string|max:255',
            'street'=>'nullable|string|max:255',
            'building'=>'nullable|string|max:255',
        ];
    }
    /**
     * Failed validation response.
     * @param mixed $validator
     */
    public function failedValidation($validator)
    {
        return ApiResponseTrait::failedValidation($validator, [], null, 422);
    }
}
