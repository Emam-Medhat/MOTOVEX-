<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Merchant spare-parts management.
 * IMPORTANT: Merchants can only manage their OWN spare parts.
 * Ownership is always set server-side via auth()->id().
 */
class SparePartController extends Controller
{
    // ── Guard: ensure user is an approved merchant ─────────────────────────
    private function requireApprovedMerchant(): void
    {
        $user = auth()->user();

        abort_unless($user->isApprovedMerchant(), 403,
            'Only approved merchants may manage spare parts.');
    }

    public function index(Request $request)
    {
        $this->requireApprovedMerchant();

        $query = SparePart::with('brand')
                          ->ownedBy(auth()->user())
                          ->latest();

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $spareParts = $query->paginate(15)->withQueryString();

        return view('account.spare-parts.index', compact('spareParts'));
    }

    public function create()
    {
        $this->requireApprovedMerchant();

        $brands      = Brand::published()->orderBy('name')->get();
        $motorcycles = Motorcycle::with('brand')->published()->orderBy('model_name')->get();

        return view('account.spare-parts.create', compact('brands', 'motorcycles'));
    }

    public function store(Request $request)
    {
        $this->requireApprovedMerchant();

        $validated = $this->validatePayload($request);

        // Server-side ownership — NEVER from form input
        $validated['created_by']  = auth()->id();
        $validated['slug']        = $this->uniqueSlug($validated['name']);

        // Merchants cannot publish — Admin approval required
        $validated['is_published'] = false;

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

        if ($request->filled('compatible_motorcycles')) {
            $sparePart->compatibleMotorcycles()->sync($request->input('compatible_motorcycles'));
        }

        return redirect()->route('account.spare-parts.index')
                         ->with('success', '"' . $sparePart->name . '" submitted for review. An admin will publish it.');
    }

    public function edit(SparePart $sparePart)
    {
        $this->requireApprovedMerchant();
        $this->authorize('update', $sparePart);

        $brands      = Brand::published()->orderBy('name')->get();
        $motorcycles = Motorcycle::with('brand')->published()->orderBy('model_name')->get();
        $selected    = $sparePart->compatibleMotorcycles->pluck('id')->toArray();

        return view('account.spare-parts.edit', compact('sparePart', 'brands', 'motorcycles', 'selected'));
    }

    public function update(Request $request, SparePart $sparePart)
    {
        $this->requireApprovedMerchant();
        $this->authorize('update', $sparePart);

        $validated = $this->validatePayload($request, $sparePart);

        // Never allow ownership change or publication via merchant form
        unset($validated['created_by']);
        $validated['is_published'] = false; // Editing un-publishes: needs re-approval

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
        $sparePart->compatibleMotorcycles()->sync(
            $request->input('compatible_motorcycles', [])
        );

        return redirect()->route('account.spare-parts.index')
                         ->with('success', '"' . $sparePart->name . '" updated. It will need re-approval before going live.');
    }

    public function destroy(SparePart $sparePart)
    {
        $this->requireApprovedMerchant();
        $this->authorize('delete', $sparePart);

        $this->deleteMainImage($sparePart);
        $sparePart->delete();

        return redirect()->route('account.spare-parts.index')
                         ->with('success', 'Spare part deleted.');
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function validatePayload(Request $request, ?SparePart $sparePart = null): array
    {
        $validated = $request->validate([
            'name'                     => ['required', 'string', 'max:255'],
            'sku'                      => ['nullable', 'string', 'max:100'],
            'brand_id'                 => ['nullable', 'integer', 'exists:brands,id'],
            'category'                 => ['nullable', 'string', 'max:100'],
            'description'              => ['nullable', 'string'],
            'compatibility_notes'      => ['nullable', 'string'],
            'price'                    => ['nullable', 'numeric', 'min:0'],
            'currency'                 => ['nullable', 'string', 'max:10'],
            'stock_quantity'           => ['nullable', 'integer', 'min:0'],
            'availability_status'      => ['nullable', Rule::in(array_keys(SparePart::$availabilityStatuses))],
            'image'                    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:3072'],
            'additional_images'        => ['nullable', 'array'],
            'additional_images.*'      => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:3072'],
            'compatible_motorcycles'   => ['nullable', 'array'],
            'compatible_motorcycles.*' => ['integer', 'exists:motorcycles,id'],
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
