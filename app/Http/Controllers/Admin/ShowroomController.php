<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showroom;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShowroomController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Showroom::class, 'showroom');
    }

    public function index()
    {
        $user = auth()->user();
        $query = Showroom::with('brand')->orderBy('name');

        if (!$user->isAdmin()) {
            $brandIds = $user->assignedBrands()->pluck('brands.id');
            $query->where(function ($q) use ($brandIds) {
                $q->whereIn('brand_id', $brandIds)
                  ->orWhereNull('brand_id');
            });
        }

        $showrooms = $query->paginate(15);
        return view('admin.showrooms.index', compact('showrooms'));
    }

    public function create()
    {
        $brands = $this->authorizedBrands();
        return view('admin.showrooms.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_id'      => ['nullable', 'exists:brands,id'],
            'name'          => ['required', 'string', 'max:255'],
            'location'      => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'email'         => ['nullable', 'email', 'max:100'],
            'opening_hours' => ['nullable', 'string'],
            'image_url'     => ['nullable', 'url'],
            'is_active'     => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);
        $validated['is_active'] = $request->has('is_active');

        Showroom::create($validated);

        return redirect()->route('admin.showrooms.index')->with('success', 'Showroom created successfully.');
    }

    public function show(Showroom $showroom)
    {
        return view('admin.showrooms.show', compact('showroom'));
    }

    public function edit(Showroom $showroom)
    {
        $brands = $this->authorizedBrands();
        return view('admin.showrooms.edit', compact('showroom', 'brands'));
    }

    public function update(Request $request, Showroom $showroom)
    {
        $validated = $request->validate([
            'brand_id'      => ['nullable', 'exists:brands,id'],
            'name'          => ['required', 'string', 'max:255'],
            'location'      => ['required', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'email'         => ['nullable', 'email', 'max:100'],
            'opening_hours' => ['nullable', 'string'],
            'image_url'     => ['nullable', 'url'],
            'is_active'     => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $showroom->update($validated);

        return redirect()->route('admin.showrooms.index')->with('success', 'Showroom updated successfully.');
    }

    public function destroy(Showroom $showroom)
    {
        $showroom->delete();
        return redirect()->route('admin.showrooms.index')->with('success', 'Showroom deleted successfully.');
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
