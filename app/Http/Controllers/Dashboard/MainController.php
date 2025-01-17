<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * return the main dashboard page view
     */
    public function index()
    {
        return view('index');
    }
}
