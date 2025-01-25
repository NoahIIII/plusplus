<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Products\StoreProductRequest;
use App\Models\Brand;
use App\Models\BusinessType;
use App\Models\Category;
use App\Services\BusinessTypeService;
use App\Services\ProductService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class PharmacyProductController extends ProductController
{
    protected $businessesTypeId;
    public function __construct(BusinessTypeService $businessTypeService, private ProductService $productService) {
        parent::__construct($businessTypeService,$productService);
        $this->businessesTypeId = $businessTypeService->getBusinessId('pharmacy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     * @param StoreProductRequest $request
     */
    public function store(StoreProductRequest $request)
    {
        $productData = $request->validated();
        $this->productService->storePharmacyProduct($productData);
        return ApiResponseTrait::apiResponse([], __('messages.added'), [], 200);
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

}

