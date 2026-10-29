<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromotionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Promotion::class, 'promotion');
    }

    public function index()
    {
        $user = auth()->user();
        $query = Promotion::with('brand')->orderByDesc('created_at');

        if (!$user->isAdmin()) {
            $brandIds = $user->assignedBrands()->pluck('brands.id');
            $query->where(function ($q) use ($brandIds) {
                $q->whereIn('brand_id', $brandIds)
                  ->orWhereNull('brand_id'); // Allow seeing global promos
            });
        }

        $promotions = $query->paginate(15);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $brands = $this->authorizedBrands();
        return view('admin.promotions.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id'    => ['nullable', 'exists:brands,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image_url'   => ['nullable', 'url'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        if ($request->filled('brand_id')) {
            $brand = Brand::findOrFail($validated['brand_id']);
            $this->authorize('manage', $brand); // Custom check if needed
        }

        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        $validated['is_active'] = $request->has('is_active');

        Promotion::create($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion created successfully.');
    }

    public function show(Promotion $promotion)
    {
        return view('admin.promotions.show', compact('promotion'));
    }

    public function edit(Promotion $promotion)
    {
        $brands = $this->authorizedBrands();
        return view('admin.promotions.edit', compact('promotion', 'brands'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'brand_id'    => ['nullable', 'exists:brands,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image_url'   => ['nullable', 'url'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $promotion->update($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion updated successfully.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return redirect()->route('admin.promotions.index')->with('success', 'Promotion deleted successfully.');
    }

    private function authorizedBrands()
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return Brand::orderBy('name')->get();
        }
        return $user->assignedBrands()->orderBy('name')->get();
    }
}
