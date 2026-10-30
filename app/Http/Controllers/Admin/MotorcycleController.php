<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMotorcycleRequest;
use App\Http\Requests\UpdateMotorcycleRequest;
use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MotorcycleController extends Controller
{
    private function authorizedBrands()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return Brand::orderBy('name')->get();
        }

        return $user->assignedBrands()->orderBy('name')->get();
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Motorcycle::class);

        $user = auth()->user();
        $query = Motorcycle::with('brand');

        if ($user->isAdmin()) {
            if ($request->filled('brand')) {
                $query->where('brand_id', $request->integer('brand'));
            }
        } else {
            $query->visibleToManager($user);
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('model_name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->input('status') === 'published');
        }

        $motorcycles = $query->orderBy('model_name')->paginate(15)->withQueryString();
        $brands = $this->authorizedBrands();

        return view('admin.motorcycles.index', compact('motorcycles', 'brands'));
    }

    public function create()
    {
        $this->authorize('create', Motorcycle::class);

        $brands = $this->authorizedBrands();
        $managers = User::whereHas('roles', fn ($query) => $query->whereIn('slug', ['data_entry', 'sales_agent']))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.motorcycles.create', compact('brands', 'managers'));
    }

    public function store(StoreMotorcycleRequest $request)
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('motorcycles', 'public');
        }

        if ($request->hasFile('additional_images')) {
            $paths = [];
            foreach ($request->file('additional_images') as $file) {
                $paths[] = $file->store('motorcycles', 'public');
            }
            $data['additional_images'] = $paths;
        } else {
            unset($data['additional_images']);
        }

        unset($data['image']);

        $data['slug'] = $this->uniqueSlug($data['model_name'] . '-' . $data['year']);
        $data['created_by'] = auth()->id();
        if (! auth()->user()->isAdmin()) {
            unset($data['assigned_manager_id']);
        }

        Motorcycle::create($data);

        return redirect()->route('admin.motorcycles.index')->with('success', 'Motorcycle created successfully.');
    }

    public function show(Motorcycle $motorcycle)
    {
        $this->authorize('view', $motorcycle);
        $motorcycle->load('brand');

        return view('admin.motorcycles.show', compact('motorcycle'));
    }

    public function edit(Motorcycle $motorcycle)
    {
        $this->authorize('update', $motorcycle);
        $brands = $this->authorizedBrands();
        $managers = User::whereHas('roles', fn ($query) => $query->whereIn('slug', ['data_entry', 'sales_agent']))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.motorcycles.edit', compact('motorcycle', 'brands', 'managers'));
    }

    public function update(UpdateMotorcycleRequest $request, Motorcycle $motorcycle)
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('motorcycles', 'public');
        }

        if ($request->hasFile('additional_images')) {
            $paths = [];
            foreach ($request->file('additional_images') as $file) {
                $paths[] = $file->store('motorcycles', 'public');
            }
            $data['additional_images'] = $paths;
        } else {
            unset($data['additional_images']);
        }

        unset($data['image']);
        if (! auth()->user()->isAdmin()) {
            unset($data['assigned_manager_id']);
        }

        $motorcycle->update($data);

        return redirect()->route('admin.motorcycles.index')->with('success', 'Motorcycle updated successfully.');
    }

    public function destroy(Motorcycle $motorcycle)
    {
        $this->authorize('delete', $motorcycle);

        $motorcycle->delete();

        return redirect()->route('admin.motorcycles.index')
            ->with('success', '"' . $motorcycle->model_name . '" has been deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $this->authorize('deleteAny', Motorcycle::class);

        $ids = $request->input('ids', []);

        if (empty($ids) || ! is_array($ids)) {
            return redirect()->route('admin.motorcycles.index')->with('error', 'No motorcycles selected.');
        }

        $user = auth()->user();
        $query = Motorcycle::whereIn('id', $ids);

        if (! $user->isAdmin()) {
            $query->visibleToManager($user);
        }

        $count = $query->delete();

        return redirect()->route('admin.motorcycles.index')->with('success', $count . ' motorcycle(s) deleted successfully.');
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);
        $original = $slug;
        $count = 1;

        while (
            Motorcycle::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
