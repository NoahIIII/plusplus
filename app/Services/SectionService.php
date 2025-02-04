<?php

namespace App\Services;

use App\Models\BusinessType;
use App\Models\Section;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectionService
{
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
}
