<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Brands\StoreBrandRequest;
use App\Http\Requests\Dashboard\Brands\UpdateBrandRequest;
use App\Models\Brand;
use App\Models\BusinessType;
use App\Services\StorageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param string $slug
     */
    public function index($slug)
    {
        // get the business type id
        $businessType = BusinessType::where('slug', $slug)
        ->select('id')
        ->first();
        $businessType ?? abort(404);
        // get the brands
        $brands = Brand::where('business_type_id', $businessType->id)
        ->paginate(20);
        return view('brands.index',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $businesses = BusinessType::all();
        return view('brands.create',compact('businesses'));
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreBrandRequest $request
     */
    public function store(StoreBrandRequest $request)
    {
        //get the request data
        $brandData = $request->validated();
        $brandData['status'] = $request->status ?? 0;
        $name = ['en'=>$request->name_en,'ar'=>$request->name_ar];
        $brandData['name'] = $name;
        //store image
        if ($request->hasFile('image')) {
            $brandData['image'] = StorageService::storeImage($request->file('image'), 'brands', 'brand-');
        }
        //create new brand
        Brand::create($brandData);
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
     * @param string $brandId
     */
    public function edit(string $brandId)
    {
        $brand = Brand::findOrFail($brandId);
        $businesses = BusinessType::all();
        return view('brands.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateBrandRequest $request
     * @param Brand $brand
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        // get the request data
        $brandData = $request->validated();
        $brandData['status'] = $request->status ?? 0;
        $name = ['en'=>$request->name_en,'ar'=>$request->name_ar];
        $brandData['name'] = $name;
        // handle the image
        if ($request->hasFile('image')) {
            $brand->image ? StorageService::deleteImage($brand->image) : null;
            $brandData['image'] = StorageService::storeImage($request->file('image'), 'brands', 'brand-');
        }
        // update brand
        $brand->update($brandData);
        return ApiResponseTrait::apiResponse([], __('messages.updated'), [], 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Brand $brand
     */
    public function destroy(Brand $brand)
    {
        if ($brand->image)  StorageService::deleteImage($brand->image);
        $brand->delete();
        return back()->with('Success', __('messages.deleted'));
    }
}
