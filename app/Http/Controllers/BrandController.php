<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::published()
            ->withCount(['motorcycles' => fn ($q) => $q->where('is_published', true)]);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $brands = $query->orderBy('name')->paginate(9)->withQueryString();

        return view('brands.index', compact('brands'));
    }

    public function show(Brand $brand)
    {
        abort_unless($brand->is_published, 404);

        $motorcycles = $brand->motorcycles()
            ->where('is_published', true)
            ->orderBy('model_name')
            ->paginate(9);

        return view('brands.show', compact('brand', 'motorcycles'));
    }
}
