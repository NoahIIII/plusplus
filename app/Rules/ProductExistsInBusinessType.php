<?php

namespace App\Rules;

use App\Models\BusinessType;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductExistsInBusinessType implements ValidationRule
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
        // Get the business type
        $businessType = BusinessType::find($this->businessTypeId);
        if (!$businessType) {
            $fail('The business type could not be found.')->translate();
            return;
        }

        // Get the model associated with this business type
        $modelClass = $businessType->model;

        // Check if the model class exists and the product ID exists in the model
        if (!class_exists($modelClass)) {
            $fail('The associated model does not exist.')->translate();
            return;
        }

        // Check if the product_id exists in the model's table
        $productExists = $modelClass::where('product_id', $value)->exists();
        if (!$productExists) {
            $fail('The selected product does not exist in the specified business type.')->translate();
        }
    }
}
