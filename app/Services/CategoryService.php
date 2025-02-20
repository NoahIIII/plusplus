<?php

namespace App\Services;

use App\Http\Requests\Dashboard\Categories\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Categories\StoreSubCategoryRequest;
use App\Http\Requests\Dashboard\Categories\StoreSubSubCategoryRequest;
use App\Models\BusinessType;
use App\Models\Category;
use App\Traits\ApiResponseTrait;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoryService
{

    /**
     * Store a category.
     *
     * @param array $data
     */
    public function store(array $data, $image)
    {
        // Combine translations for the name
        $data['name'] = [
            'en' => $data['name_en'] ?? '',
            'ar' => $data['name_ar'] ?? ''
        ];

        // Set default status if not provided
        $data['status'] = $data['status'] ?? 0;

        // Determine business type and category level
        if (!isset($data['business_type_id']) && isset($data['parent_id'])) {
            $parentCategory = Category::find($data['parent_id']);
            if ($parentCategory) {
                $data['business_type_id'] = $parentCategory->business_type_id;
                $data['level'] = $parentCategory->level + 1;
            }
        } else {
            $data['level'] = 1; // Main category
            $data['parent_id'] = null;
        }
        $data['image'] = StorageService::storeImage($image, 'categories', 'category-');

        // Create and return the category
        Category::create($data);
    }

    /**
     * update category
     * @param array $data
     */
    public function update(Category $category, array $data, $image = null)
    {
        // Combine translations for the name
        $data['name'] = [
            'en' => $data['name_en'] ?? '',
            'ar' => $data['name_ar'] ?? ''
        ];

        // Set default status if not provided
        $data['status'] = $data['status'] ?? 0;
        // handle the image
        if ($image) {
            if ($category->image) StorageService::deleteImage($category->image);
            $data['image'] = StorageService::storeImage($image, 'categories', 'category-');
        }
        $category->update($data);
    }
    /**
     * Validate the category based on the level.
     *
     * @param $request
     * @return mixed
     */
    public function validateCategory($request)
    {
        $level = $request->input('level');
        // dd($level);

        // Determine which validation request to use based on the category level
        switch ($level) {
            case 1:
                return $request->validate((new StoreCategoryRequest)->rules());

            case 2:
                return $request->validate((new StoreSubCategoryRequest)->rules());

            case 3:
                return $request->validate((new StoreSubSubCategoryRequest)->rules());

            default:
                return response()->json(['error' => 'Invalid category level', 'level' => $request->level], 400);
        }
    }

    /**
     * get categories (for dashboard view)
     * @param Request $request
     * @param mixed $slug
     */
    public function getCategoriesBySlug($request, $slug)
    {
        // get the business type id
        $businessType = BusinessType::where('slug', $slug)
            ->select('id')
            ->first();
        $businessType ?? abort(404);
        $parentId = $request->input('parent_id') ?? null;
        $level = 1;
        if ($parentId) {
            $parentCategory = Category::find($parentId);
            $parentCategory ?? abort(404);
            $level = $parentCategory->level + 1;
            if ($level > 3) abort(404);
        }
        return Category::where('business_type_id', $businessType->id)
            ->where('parent_id', $parentId)
            ->where('level', $level)
            ->paginate(20);
    }

    /**
     * Get categories by level and return them in the current locale.
     *
     * @param int $level
     * @return \Illuminate\Support\Collection
     * @throws ValidationException
     */
    public function getCategoriesByLevel(int $level)
    {
        // Validate the level
        if (!in_array($level, [1, 2, 3])) {
            throw new ValidationException("Invalid category level");
        }

        // Get the current locale
        $locale = app()->getLocale();
        // Fetch categories based on the level
        return Category::where('level', $level)
            ->get(['category_id', 'name']) // Fetch only the ID and name columns
            ->map(function ($category) use ($locale) {
                return [
                    'id' => $category->category_id,
                    'name' => $category->getTranslation('name', $locale), // Get name based on current locale
                ];
            });
    }

    /**
     * get categories by parent id & level and business Type id
     */
    public function getCategories($parentId = null, $businessTypeId)
    {
        $level = 1; // default level
        // check if the parent category exists
        if ($parentId != null) {
            $parentCategory = Category::where('category_id', $parentId)
                ->where('status', 1)
                ->select('category_id', TranslateService::localizedColumn('name'),'level')
                ->first();
                if(!$parentCategory) return ApiResponseTrait::errorResponse(__('messages.not-found'),404);
                $level = $parentCategory->level + 1;
        }
        // get the categories
        return Category::where('business_type_id', $businessTypeId)
            ->where('level', $level)
            ->select('category_id', TranslateService::localizedColumn('name'), 'image', 'level','parent_id')
            ->where('status', 1)
            ->where('parent_id', $parentId)
            ->withCount('children')
            ->get()
            ->map(function ($category) {
                $category->has_child = $category->children_count > 0;
                unset($category->children_count);
                return $category;
            });
    }
}
