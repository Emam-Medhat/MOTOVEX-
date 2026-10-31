<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Brand::class);

        $user = auth()->user();

        $query = Brand::withCount('motorcycles');

        if (! $user->isAdmin()) {
            $brandIds = $user->assignedBrands()->pluck('brands.id');
            $query->whereIn('id', $brandIds);
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $brands = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        $this->authorize('create', Brand::class);

        return view('admin.brands.create');
    }

    public function store(StoreBrandRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('logo')) {
            $data['logo_url'] = $request->file('logo')->store('brands', 'public');
        }
        unset($data['logo']);

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function show(Brand $brand)
    {
        $this->authorize('view', $brand);

        $motorcycles = $brand->motorcycles()->orderBy('model_name')->paginate(10);

        return view('admin.brands.show', compact('brand', 'motorcycles'));
    }

    public function edit(Brand $brand)
    {
        $this->authorize('update', $brand);

        return view('admin.brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['name'], $brand->id);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('logo')) {
            // Delete old logo if it's a stored file (not an external URL)
            if ($brand->logo_url && !str_starts_with($brand->logo_url, 'http')) {
                Storage::disk('public')->delete($brand->logo_url);
            }
            $data['logo_url'] = $request->file('logo')->store('brands', 'public');
        }
        unset($data['logo']);

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        $this->authorize('delete', $brand);

        if ($brand->motorcycles()->exists()) {
            return redirect()
                ->route('admin.brands.index')
                ->with('error', 'Cannot delete this brand while motorcycles are linked. Remove or reassign them first.');
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }

    public function assignUsers(Brand $brand)
    {
        $this->authorize('assignUsers', Brand::class);

        $users = User::whereHas('roles', fn ($q) => $q->where('slug', 'data_entry'))
            ->orderBy('name')
            ->get();

        $assignedUserIds = $brand->users()->pluck('users.id');

        return view('admin.brands.assign', compact('brand', 'users', 'assignedUserIds'));
    }

    public function syncUsers(Request $request, Brand $brand)
    {
        $this->authorize('assignUsers', Brand::class);

        $request->validate([
            'user_ids'   => ['nullable', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        $userIds = $request->input('user_ids', []);
        $syncData = collect($userIds)->mapWithKeys(fn ($id) => [$id => ['role' => 'data_entry']])->all();

        $brand->users()->sync($syncData);

        return redirect()->route('admin.brands.show', $brand)->with('success', 'Users assigned successfully.');
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $count = 1;

        while (
            Brand::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}
