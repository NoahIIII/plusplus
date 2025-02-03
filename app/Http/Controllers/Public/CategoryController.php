<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\TranslateService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $businessTypeId;
    public function __construct(private CategoryService $categoryService)
    {
        $this->businessTypeId = auth('users')->user()->business_type_id ?? 1;
    }

    /**
     * get all main categories for a business
     */
    public function getMainCategories()
    {
        // get the categories
        $categories = $this->categoryService->getCategories(null,  $this->businessTypeId);
        if ($categories instanceof JsonResponse) return $categories;
        return ApiResponseTrait::successResponse(['categories' => $categories]);
    }
    /**
     * get all sub categories for a business
     */
    public function getChildCategories($categoryId)
    {
        $categories = $this->categoryService->getCategories($categoryId,  $this->businessTypeId);
        if ($categories instanceof JsonResponse) return $categories;
        return ApiResponseTrait::successResponse(['categories' => $categories]);
    }

}
