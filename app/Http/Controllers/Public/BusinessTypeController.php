<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BusinessType;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class BusinessTypeController extends Controller
{
    /**
     * get all business types
     */
    public function getBusinessTypes()
    {
        return ApiResponseTrait::successResponse(['business_types' => BusinessType::all('id', 'slug')]);
    }
}
