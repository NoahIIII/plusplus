<?php

namespace App\Services;

use App\Http\Requests\Dashboard\Categories\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Categories\StoreSubCategoryRequest;
use App\Http\Requests\Dashboard\Categories\StoreSubSubCategoryRequest;
use App\Models\Category;
use Dotenv\Exception\ValidationException;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CategoryService
{


    /**
     * Store a category.
     *
     * @param array $data
     */
    public function store(array $data)
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

        // Create and return the category
        Category::create($data);
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
                return response()->json(['error' => 'Invalid category level','level'=>$request->all()], 400);
        }
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

        Category::where('level', $level)
            ->get(['category_id', 'name']);
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
}
