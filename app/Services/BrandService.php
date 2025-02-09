<?php 
namespace App\Services;

use App\Models\Brand;
use App\Models\BusinessType;
use Dotenv\Exception\ValidationException;


class BrandService
{
    /**
     * fetch brands by business type id
     */
    public function getBrandsByBusinessType($businessTypeId)
    {
        $businessType = BusinessType::find($businessTypeId);
        if (!$businessType) {
            throw new ValidationException(__('messages.not-found'));
        }
        return Brand::get(['brand_id', 'name'])
            ->map(function ($brand) {
                return [
                    'id' => $brand->brand_id,
                    'name' => $brand->name,
                ];
            });
    }
}