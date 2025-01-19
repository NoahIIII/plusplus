<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Categories\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Categories\StoreSubCategoryRequest;
use App\Http\Requests\Dashboard\Categories\StoreSubSubCategoryRequest;
use App\Models\BusinessType;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\ApiResponseTrait;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct(private CategoryService $categoryService) {

    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    /**
     * return the create category form
     */
    public function create()
    {
        $businesses = BusinessType::all();
        return view('categories.create', compact('businesses'));
    }

    /**
     * Store Main Category
     * @param Request $request
     */
    public function store(Request $request)

    {
        // dd($request->all());
        // handle the validation (different validation for each category level)
        $categoryData = $this->categoryService->validateCategory($request);
        if ($categoryData instanceof \Illuminate\Http\JsonResponse) {
            return $categoryData;
        }
        // store the category
        $this->categoryService->store($categoryData);
        return ApiResponseTrait::apiResponse([], __('messages.added'), [], 200);
    }
    /**
     * Get categories based on the level.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategoriesByLevel(Request $request)
    {
        // Get the level from the request
        $level = $request->input('level');

        // Fetch categories
        try {
            $categories = $this->categoryService->getCategoriesByLevel($level);
            return response()->json($categories);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
