<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\Service;
use App\Models\SparePart;

class HomeController extends Controller
{
    public function index()
    {
        $brands = Brand::published()
            ->withCount(['motorcycles' => fn ($q) => $q->where('is_published', true)])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(8)
            ->get();

        $motorcycles = Motorcycle::with('brand')
            ->published()
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        $spareParts = SparePart::with('brand')
            ->published()
            ->available()
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $services = Service::active()->take(8)->get();

        $footerBrands = Brand::published()
            ->withCount(['motorcycles' => fn ($q) => $q->where('is_published', true)])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(5)
            ->get();

        return view('home', compact('brands', 'motorcycles', 'spareParts', 'services', 'footerBrands'));
    }
}
