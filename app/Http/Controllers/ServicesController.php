<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Service;

class ServicesController extends Controller
{
    public function index()
    {
        // Active services only, ordered by their configured display order.
        $services = Service::active()->get();

        $footerBrands = Brand::published()->take(6)->get();

        return view('services', compact('services', 'footerBrands'));
    }
}
