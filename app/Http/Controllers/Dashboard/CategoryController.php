<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Categories\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Categories\StoreSubCategoryRequest;
use App\Http\Requests\Dashboard\Categories\StoreSubSubCategoryRequest;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    //----------------------------- Common Functions -----------------------------
    /**
     * Store Categories
     */
    private function store($data)
    {
        /*
        Level 1 Is the Main Category
        Level 2 Is the Sub Category....etc
        */
        $data['name'] = ['en' => $data['name_en'], 'ar' => $data['name_ar']];
        // handle the status
        if (!isset($data['status'])) {
            $data['status'] = 0;
        }
        // set the business type & category level
        if (!isset($data['business_type_id']) && isset($data['parent_id'])) {
            $parentCategory = Category::find($data['parent_id']);
            $data['business_type_id'] = $parentCategory->business_type_id;
            $data['level'] = $parentCategory->level + 1;
        } else {
            $data['level'] = 1; // main category
        }
        Category::create($data);
    }

    //----------------------------- Main Category Functions -----------------------------
    /**
     * Store Main Category
     * @param StoreCategoryRequest $request
     */
    public function storeMainCategory(StoreCategoryRequest $request)
    {
        // get request data
        $categoryData = $request->validated();
        $categoryData['parent_id'] = null;
        // store the category
        $this->store($categoryData);
        return ApiResponseTrait::apiResponse([], __('messages.added'), [], 200);
    }

    //----------------------------- Sub Category Functions -----------------------------
    /**
     * Store Sub Category
     * @param StoreSubCategoryRequest $request
     */
    public function storeSubCategory(StoreCategoryRequest $request)
    {
        // get request data
        $categoryData = $request->validated();
        // store the category
        $this->store($categoryData);
        return ApiResponseTrait::apiResponse([], __('messages.added'), [], 200);
    }

    //----------------------------- Sub Sub Category Functions -----------------------------
    /**
     * Store Sub Sub Category
     * @param StoreSubSubCategoryRequest $request
     */
    public function storeSubSubCategory(StoreSubSubCategoryRequest $request)
    {
        // get request data
        $categoryData = $request->validated();
        // store the category
        $this->store($categoryData);
        return ApiResponseTrait::apiResponse([], __('messages.added'), [], 200);
    }
}
