<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Admin: manages ALL spare parts.
 * Merchant (accessed via Account\SparePartController): manages only their own.
 */
class SparePartController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', SparePart::class);

        $query = SparePart::with(['brand', 'creator'])->latest();

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%");
            });
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->integer('brand'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->input('status') === 'published');
        }

        if ($request->filled('owner')) {
            $query->where('created_by', $request->integer('owner'));
        }

        $spareParts = $query->paginate(20)->withQueryString();
        $brands     = Brand::orderBy('name')->get();

        return view('admin.spare-parts.index', compact('spareParts', 'brands'));
    }

    public function create()
    {
        $this->authorize('create', SparePart::class);

        $brands      = Brand::orderBy('name')->get();
        $motorcycles = Motorcycle::with('brand')->published()->orderBy('model_name')->get();

        return view('admin.spare-parts.create', compact('brands', 'motorcycles'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', SparePart::class);

        $validated = $this->validatePayload($request);

        $validated['slug']        = $this->uniqueSlug($validated['name']);
        $validated['created_by']  = auth()->id();  // ALWAYS server-side
        $validated['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('spare-parts', 'public');
        }

        if ($request->hasFile('additional_images')) {
            $paths = [];
            foreach ($request->file('additional_images') as $file) {
                $paths[] = $file->store('spare-parts', 'public');
            }
            $validated['additional_images'] = $paths;
        }

        $sparePart = SparePart::create($validated);

        // Sync compatible motorcycles
        if ($request->filled('compatible_motorcycles')) {
            $sparePart->compatibleMotorcycles()->sync($request->input('compatible_motorcycles'));
        }

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', '"' . $sparePart->name . '" created successfully.');
    }

    public function show(SparePart $sparePart)
    {
        $this->authorize('view', $sparePart);
        $sparePart->load(['brand', 'creator', 'compatibleMotorcycles.brand']);

        return view('admin.spare-parts.show', compact('sparePart'));
    }

    public function edit(SparePart $sparePart)
    {
        $this->authorize('update', $sparePart);

        $brands      = Brand::orderBy('name')->get();
        $motorcycles = Motorcycle::with('brand')->published()->orderBy('model_name')->get();
        $selected    = $sparePart->compatibleMotorcycles->pluck('id')->toArray();

        return view('admin.spare-parts.edit', compact('sparePart', 'brands', 'motorcycles', 'selected'));
    }

    public function update(Request $request, SparePart $sparePart)
    {
        $this->authorize('update', $sparePart);

        $validated = $this->validatePayload($request, $sparePart);
        $validated['is_published'] = $request->boolean('is_published');

        // Never allow changing the owner via the form
        unset($validated['created_by']);

        if ($request->hasFile('image')) {
            $this->deleteMainImage($sparePart);
            $validated['image_path'] = $request->file('image')->store('spare-parts', 'public');
        }

        if ($request->hasFile('additional_images')) {
            $paths = [];
            foreach ($request->file('additional_images') as $file) {
                $paths[] = $file->store('spare-parts', 'public');
            }
            $validated['additional_images'] = $paths;
        }

        $sparePart->update($validated);

        // Sync compatible motorcycles
        $sparePart->compatibleMotorcycles()->sync(
            $request->input('compatible_motorcycles', [])
        );

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', '"' . $sparePart->name . '" updated successfully.');
    }

    public function destroy(SparePart $sparePart)
    {
        $this->authorize('delete', $sparePart);

        $this->deleteMainImage($sparePart);
        $sparePart->delete();

        return redirect()->route('admin.spare-parts.index')
                         ->with('success', 'Spare part deleted successfully.');
    }

    /**
     * Toggle publish / unpublish (Admin only).
     */
    public function togglePublish(SparePart $sparePart)
    {
        $this->authorize('publish', $sparePart);

        $sparePart->update(['is_published' => ! $sparePart->is_published]);

        return back()->with('success', $sparePart->is_published ? 'Spare part published.' : 'Spare part unpublished.');
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function validatePayload(Request $request, ?SparePart $sparePart = null): array
    {
        $validated = $request->validate([
            'name'                    => ['required', 'string', 'max:255'],
            'sku'                     => ['nullable', 'string', 'max:100'],
            'brand_id'                => ['nullable', 'integer', 'exists:brands,id'],
            'category'                => ['nullable', 'string', 'max:100'],
            'description'             => ['nullable', 'string'],
            'compatibility_notes'     => ['nullable', 'string'],
            'price'                   => ['nullable', 'numeric', 'min:0'],
            'currency'                => ['nullable', 'string', 'max:10'],
            'stock_quantity'          => ['nullable', 'integer', 'min:0'],
            'availability_status'     => ['nullable', Rule::in(array_keys(SparePart::$availabilityStatuses))],
            'image'                   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:3072'],
            'additional_images'       => ['nullable', 'array'],
            'additional_images.*'     => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:3072'],
            'compatible_motorcycles'  => ['nullable', 'array'],
            'compatible_motorcycles.*' => ['integer', 'exists:motorcycles,id'],
            'is_published'            => ['nullable', 'boolean'],
        ]);

        unset($validated['image'], $validated['additional_images'], $validated['compatible_motorcycles']);

        return $validated;
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug     = Str::slug($base) ?: Str::random(8);
        $original = $slug;
        $i        = 1;

        while (
            SparePart::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }

    private function deleteMainImage(SparePart $sparePart): void
    {
        if ($sparePart->image_path && Storage::disk('public')->exists($sparePart->image_path)) {
            Storage::disk('public')->delete($sparePart->image_path);
        }
    }
}
