<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Categories\StoreSubSubCategoryRequest;
use App\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class SubSubCategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    /**
     * Store Sub Sub Category
     * @param StoreSubSubCategoryRequest $request
     */
    public function store(StoreSubSubCategoryRequest $request)
    {
        // get request data
        $categoryData = $request->validated();
        // store the category
        $this->categoryService->store($categoryData);
        return ApiResponseTrait::apiResponse([], __('messages.added'), [], 200);
    }
}
