<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Service::class, 'service');
    }

    public function index()
    {
        $services = Service::orderBy('sort_order')->orderBy('title')->paginate(15);

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        $validated['slug']      = $this->uniqueSlug($validated['slug'] ?? $validated['title']);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $this->validatePayload($request, $service);

        $validated['slug']      = $this->uniqueSlug($validated['slug'] ?? $validated['title'], $service->id);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $this->deleteImage($service);
            $validated['image_path'] = $request->file('image')->store('services', 'public');
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    /**
     * Publish / unpublish a service without leaving the list.
     */
    public function toggleActive(Service $service)
    {
        $this->authorize('update', $service);

        $service->update(['is_active' => ! $service->is_active]);

        return back()->with('success', $service->is_active ? 'Service published.' : 'Service unpublished.');
    }

    public function destroy(Service $service)
    {
        $this->deleteImage($service);
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePayload(Request $request, ?Service $service = null): array
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', Rule::unique('services', 'slug')->ignore($service?->id)],
            'icon'             => ['nullable', 'string', 'max:100'],
            'description'      => ['required', 'string'],
            'full_description' => ['nullable', 'string'],
            'cta_label'        => ['nullable', 'string', 'max:100'],
            'cta_route'        => ['nullable', 'string', 'max:255'],
            'sort_order'       => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'is_active'        => ['nullable', 'boolean'],
        ]);

        // The uploaded file is handled separately (stored to disk).
        unset($validated['image']);

        return $validated;
    }

    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug     = Str::slug($base) ?: Str::random(8);
        $original = $slug;
        $i        = 1;

        while (
            Service::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }

    private function deleteImage(Service $service): void
    {
        if ($service->image_path && Storage::disk('public')->exists($service->image_path)) {
            Storage::disk('public')->delete($service->image_path);
        }
    }
}
