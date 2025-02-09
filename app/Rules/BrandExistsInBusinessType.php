<?php

namespace App\Rules;

use App\Models\Brand;
use App\Models\BusinessType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BrandExistsInBusinessType implements ValidationRule
{

    /**
     * The business type ID.
     *
     * @var int
     */
    protected $businessTypeId;

    /**
     * Create a new rule instance.
     *
     * @param  int  $businessTypeId
     * @return void
     */
    public function __construct($businessTypeId)
    {
        $this->businessTypeId = $businessTypeId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the product_id exists in the model's table
        $brandExists = Brand::where('brand_id', $value)
        ->where('business_type_id',$this->businessTypeId)
        ->exists();
        if (!$brandExists) {
            $fail('The selected product does not exist in the specified business type.')->translate();
        }
    }
}
