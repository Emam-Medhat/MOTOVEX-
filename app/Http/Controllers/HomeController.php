<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Motorcycle;

class HomeController extends Controller
{
    public function index()
    {
        $featuredMotorcycle = Motorcycle::with('brand')
            ->published()
            ->featured()
            ->orderByDesc('horsepower')
            ->first()
            ?? Motorcycle::with('brand')
                ->published()
                ->orderByDesc('horsepower')
                ->first();

        $latestMotorcycles = Motorcycle::with('brand')
            ->published()
            ->orderByDesc('created_at')
            ->take(4)
            ->get();

        $brands = Brand::published()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(6)
            ->get();

        return view('home', compact('featuredMotorcycle', 'latestMotorcycles', 'brands'));
    }
}
