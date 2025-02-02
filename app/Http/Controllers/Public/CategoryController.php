<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\TranslateService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $businessTypeId;
    public function __construct() {
        $this->businessTypeId = auth('users')->user()->business_type_id ?? 1;
    }

    /**
     * get all categories for a business
     * @return void
     */
    public function getCategories()
    {
        // get the categories
        $categories = Category::where('business_type_id', $this->businessTypeId)
        ->select('category_id', TranslateService::localizedColumn('name'),'image','level','parent_id')
        ->get();
        return ApiResponseTrait::successResponse(['categories' => $categories]);
    }
}
