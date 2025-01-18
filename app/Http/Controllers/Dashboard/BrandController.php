<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreBrandRequest;
use App\Models\Brand;
use App\Services\StorageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::paginate(20);
        return view('brands.index',compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');
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
    public function destroy(string $brandId)
    {

    }
}
