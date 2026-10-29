<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MotorcycleController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search'   => ['nullable', 'string', 'max:100'],
            'brand'    => ['nullable', 'integer', 'exists:brands,id'],
            'category' => ['nullable', 'string', 'max:50'],
            'min_cc'   => ['nullable', 'numeric', 'min:0'],
            'max_cc'   => ['nullable', 'numeric', 'min:0'],
            'sort'     => ['nullable', Rule::in(['name', 'price_asc', 'price_desc', 'power'])],
        ]);

        $query = Motorcycle::with('brand')->published();

        if (! empty($validated['brand'])) {
            $query->where('brand_id', $validated['brand']);
        }

        if (! empty($validated['category'])) {
            $query->where('category', $validated['category']);
        }

        if (! empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('model_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if (isset($validated['min_cc'])) {
            $query->where('engine_capacity', '>=', $validated['min_cc']);
        }

        if (isset($validated['max_cc'])) {
            $query->where('engine_capacity', '<=', $validated['max_cc']);
        }

        match ($validated['sort'] ?? null) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'power'      => $query->orderByDesc('horsepower'),
            default      => $query->orderBy('model_name'),
        };

        $motorcycles = $query->paginate(9)->withQueryString();
        $brands      = Brand::published()->withCount('motorcycles')->orderBy('name')->get();
        $categories  = Motorcycle::published()->distinct()->orderBy('category')->pluck('category');
        $footerBrands = Brand::published()->take(6)->get();

        return view('motorcycles.index', compact('motorcycles', 'brands', 'categories', 'footerBrands'));
    }

    public function show(Motorcycle $motorcycle)
    {
        abort_unless(
            $motorcycle->is_published && $motorcycle->brand && $motorcycle->brand->is_published,
            404
        );

        $related = Motorcycle::with('brand')
            ->where('brand_id', $motorcycle->brand_id)
            ->where('id', '!=', $motorcycle->id)
            ->where('is_published', true)
            ->limit(3)
            ->get();

        $footerBrands = Brand::published()->take(6)->get();

        return view('motorcycles.show', compact('motorcycle', 'related', 'footerBrands'));
    }
}
