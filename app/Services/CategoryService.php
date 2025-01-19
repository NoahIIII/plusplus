<?php

namespace App\Services;

use App\Models\Category;

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
        }

        // Create and return the category
        Category::create($data);
    }
}
