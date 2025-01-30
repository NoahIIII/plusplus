<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreDiscountRequest;
use App\Http\Requests\Dashboard\UpdateDiscountRequest;
use App\Models\BusinessType;
use App\Models\Discount;
use App\Models\ProductDiscount;
use App\Services\BusinessTypeService;
use App\Services\DiscountService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class DiscountController extends Controller
{

    public function __construct(private DiscountService $discountService,private BusinessTypeService $businessTypeService){}
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        $businessTypeId = $this->businessTypeService->getBusinessId($slug);
        $discounts = Discount::discount() // scope to get the not coupon discounts
        ->where('business_type_id', $businessTypeId)
        ->orderBy('created_at', 'desc')
        ->paginate(20);
        return view('discounts.index',compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $businesses = BusinessType::all();
        return view('discounts.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreDiscountRequest $request
     */
    public function store(StoreDiscountRequest $request)
    {
        $discountData = $request->validated();
        //store discount
        $this->discountService->storeDiscount($discountData);
        return ApiResponseTrait::successResponse([], __('messages.added'));
    }
    /**
     * return the edit discount form
     *
     * @param int $discountId
     */
    public function edit($discountId)
    {
        $discount = Discount::with('productDiscounts')->findOrFail($discountId);
        $businesses = BusinessType::all();
        return view('discounts.edit',get_defined_vars());
    }
    /**
     * update discount
     *
     * @param Discount $discount
     * @param UpdateDiscountRequest $request
     */
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        $discountData = $request->validated();
        $this->discountService->updateDiscount($discount, $discountData);
        return ApiResponseTrait::successResponse([], __('messages.updated'));
    }
    /**
     * destroy discount
     */
    public function destroy(Discount $discount)
    {
        // delete all the products discount
        ProductDiscount::where('discount_id', $discount->discount_id)->delete();
        $discount->delete();
        return back()->with('Success', __('messages.deleted'));
    }
}
