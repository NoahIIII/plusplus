<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BusinessType;
use App\Models\Section;
use App\Models\SectionProduct;
use App\Services\DiscountService;
use App\Services\ProductService;
use App\Services\TranslateService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    protected $businessTypeId;
    protected $businessTypeModel;
    protected $businessTypeTable;
    public function __construct()
    {
        $this->businessTypeId = auth('users')->user()->business_type_id ?? 1;
        $this->businessTypeModel = BusinessType::where('id', $this->businessTypeId)->value('model');
        $this->businessTypeTable = (new $this->businessTypeModel)->getTable();
    }
    /**
     * get all sections
     */
    public function getAllSections()
    {
        $sections = Section::where('business_type_id', $this->businessTypeId)
            ->where('status', 1)
            ->select(
                'section_id',
                TranslateService::localizedColumn('name'),
                'first_color',
                'second_color'
            )
            ->get();
        return ApiResponseTrait::successResponse(['sections' => $sections]);
    }
    /**
     * get section products
     * @param int $sectionId
     */
    public function getSectionProducts($sectionId)
    {
        // check if the section exists
        $section = Section::find($sectionId);
        if (!$section) {
            return ApiResponseTrait::errorResponse(__('messages.not-found'), 404);
        }

        // get all products in the section
        $discountService = new DiscountService(); // instantiate discount service
        $products = $this->businessTypeModel::join('section_products', 'section_products.productable_id', '=', "{$this->businessTypeTable}.product_id")
            ->where('section_products.section_id', $sectionId)
            ->where('section_products.productable_type', $this->businessTypeModel)
            ->select(
                "{$this->businessTypeTable}.product_id",
                TranslateService::localizedColumn('name'),
                "{$this->businessTypeTable}.price",
                "{$this->businessTypeTable}.primary_image"
            )
            ->get()

            // add discount details
            ->map(function ($product) use ($discountService) {
                $discountDetails = $discountService->getProductDiscount($product, $this->businessTypeId);
                $product->discounted_price = $discountDetails['discounted_price'];
                $product->discount_value = $discountDetails['discount_value'];
                return $product;
            });

        return ApiResponseTrait::successResponse(['section_id' => (int)$sectionId, 'products' => $products]);
    }
}
