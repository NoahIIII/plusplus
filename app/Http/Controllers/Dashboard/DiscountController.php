<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreDiscountRequest;
use App\Models\BusinessType;
use App\Services\DiscountService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService){}
    /**
     * Display a listing of the resource.
     */
    public function getDiscounts()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createDiscount()
    {
        $businesses = BusinessType::all();
        return view('discounts.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreDiscountRequest $request
     */
    public function storeDiscount(StoreDiscountRequest $request)
    {
        // $discountData = $request->validated();
        // // dd($discountData);
        // //store discount
        // $this->discountService->storeDiscount($discountData);
        // return ApiResponseTrait::successResponse([], __('messages.added'));
    }
}
