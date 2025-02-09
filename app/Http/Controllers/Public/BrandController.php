<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BusinessType;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected $businessTypeId;
    protected $businessTypeModel;
    public function __construct() {
        $this->businessTypeId = auth('users')->user()->business_type_id ?? 1;
        $this->businessTypeModel = BusinessType::where('id', $this->businessTypeId)->value('model');
    }
    /**
     * get all brands 
     */
    public function getAllBrands()
    {
        // $brands = 
    }
}
