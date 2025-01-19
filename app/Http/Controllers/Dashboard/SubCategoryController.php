<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Categories\StoreSubCategoryRequest;
use App\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    /**
     * Store Sub Category
     * @param StoreSubCategoryRequest $request
     */
    public function store(StoreSubCategoryRequest $request)
    {
        // get request data
        $categoryData = $request->validated();
        // store the category
        $this->categoryService->store($categoryData);
        return ApiResponseTrait::apiResponse([], __('messages.added'), [], 200);
    }
}
