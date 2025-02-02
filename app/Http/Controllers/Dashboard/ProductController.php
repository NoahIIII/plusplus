<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PackageType;
use App\Enums\UnitType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Products\StoreProductRequest;
use App\Http\Requests\Dashboard\Products\UpdateProductRequest;
use App\Models\Brand;
use App\Models\BusinessType;
use App\Models\Category;
use App\Services\BusinessTypeService;
use App\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $businessTypeSlug;
    protected $businessesTypeId;

    public function __construct(private BusinessTypeService $businessTypeService, private ProductService $productService)
    {
        $this->businessTypeSlug = $businessTypeService->determineBusinessTypeSlug();
        $this->businessesTypeId = $businessTypeService->getBusinessId($this->businessTypeSlug);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("products.$this->businessTypeSlug.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::where('business_type_id', $this->businessesTypeId)->get();
        $categories = Category::where('business_type_id', $this->businessesTypeId)
            ->where('level', 3)
            ->get();
        $packageTypes = PackageType::values();
        $unitTypes = UnitType::values();
        return view("products.$this->businessTypeSlug.create", get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }

    /**
     * fetch products data
     */
    public function getProductsByBusinessType($businessTypeId)
    {
        try {
            $products = $this->productService->getProductsByBusinessType($businessTypeId);
            return ApiResponseTrait::successResponse($products);
        } catch (\Exception $e) {
            return ApiResponseTrait::errorResponse($e->getMessage(),422);
        }
    }
}
