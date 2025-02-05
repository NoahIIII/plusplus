<?php

namespace App\Services;

use App\Models\BusinessType;
use App\Models\Section;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectionService
{
    /**
     * store a service
     * @param mixed $data
     */
    public function storeService($data)
    {
        $sectionData = TranslateService::getTranslatableData($data, ['name']);
        unset($sectionData['product_ids']);
        // get the business model
        try {
            $businessType = BusinessType::find($data['business_type_id']);
            DB::beginTransaction();
            $section = Section::create($sectionData);
            $section->sectionProducts()->createMany(
                collect($data['product_ids'])->map(function ($productId) use ($businessType) {
                    return [
                        'productable_type' => $businessType->model,  // Ensure this holds the full class name like 'App\Models\Product'
                        'productable_id' => $productId,
                    ];
                })->toArray()
            );
            DB::commit();
            return $section;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error while creating section: " . $e->getMessage());
            return ApiResponseTrait::errorResponse(__('messages.error'), 400);
        }
    }
    /**
     * update a service
     * @param mixed $section
     * @param mixed $data
     */
    public function updateService($section, $data)
    {
        $sectionData = TranslateService::getTranslatableData($data, ['name']);
        unset($sectionData['product_ids']);
        try {
            // get the business model
            $businessType = BusinessType::find($data['business_type_id']);
            // start transaction
            DB::beginTransaction();
            // update section data
            $section->update($sectionData);
            // sync product section data
            $existingProductIds = $section->sectionProducts()->pluck('productable_id')->toArray();
            $newProductIds = $data['product_ids'];
        
            // Identify products to be added and removed
            $productsToAdd = array_diff($newProductIds, $existingProductIds);
            $productsToRemove = array_diff($existingProductIds, $newProductIds);
        
            // Remove only the necessary products
            if (!empty($productsToRemove)) {
                $section->sectionProducts()->whereIn('productable_id', $productsToRemove)->delete();
            }
        
            // Add new products
            if (!empty($productsToAdd)) {
                $section->sectionProducts()->createMany(
                    collect($productsToAdd)->map(function ($productId) use ($businessType) {
                        return [
                            'productable_type' => $businessType->model,
                            'productable_id' => $productId,
                        ];
                    })->toArray()
                );
            }
            DB::commit();
            return $section;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error while updating section: " . $e->getMessage());
            return ApiResponseTrait::errorResponse(__('messages.error'), 400);
        }
    }
}
