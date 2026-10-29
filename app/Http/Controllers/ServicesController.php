<?php

namespace App\Http\Controllers;

use App\Models\Brand;

class ServicesController extends Controller
{
    public function index()
    {
        $footerBrands = Brand::published()->take(6)->get();
        return view('services', compact('footerBrands'));
    }
}
