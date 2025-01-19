<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Categories\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Categories\StoreSubCategoryRequest;
use App\Http\Requests\Dashboard\Categories\StoreSubSubCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct(private CategoryService $categoryService)
    {
    }


    /**
     * Store Main Category
     * @param StoreCategoryRequest $request
     */
    public function store(StoreCategoryRequest $request)
    {
        // get request data
        $categoryData = $request->validated();
        $categoryData['parent_id'] = null;
        // store the category
        $this->categoryService->store($categoryData);
        return ApiResponseTrait::apiResponse([], __('messages.added'), [], 200);
    }
}
