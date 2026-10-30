<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\SparePart;
use Illuminate\Http\Request;

class SparePartsController extends Controller
{
    /**
     * Public spare parts listing page: /spare-parts
     */
    public function index(Request $request)
    {
        $query = SparePart::with(['brand', 'compatibleMotorcycles'])
                          ->published()
                          ->available();

        // ── Search ────────────────────────────────────────────────────────
        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        // ── Category filter ───────────────────────────────────────────────
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // ── Brand filter ──────────────────────────────────────────────────
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->integer('brand'));
        }

        // ── Motorcycle compatibility filter ───────────────────────────────
        if ($request->filled('motorcycle')) {
            $motorcycleId = $request->integer('motorcycle');
            $query->whereHas('compatibleMotorcycles', fn ($q) => $q->where('motorcycles.id', $motorcycleId));
        }

        // ── Sort ──────────────────────────────────────────────────────────
        match ($request->input('sort', 'newest')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc'   => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $spareParts  = $query->paginate(12)->withQueryString();
        $brands      = Brand::published()->orderBy('name')->get();
        $motorcycles = Motorcycle::with('brand')->published()->orderBy('model_name')->get();
        $categories  = SparePart::$categories;

        return view('spare-parts.index', compact(
            'spareParts', 'brands', 'motorcycles', 'categories'
        ));
    }

    /**
     * Public spare part detail page: /spare-parts/{sparePart:slug}
     */
    public function show(SparePart $sparePart)
    {
        // Only published parts are publicly accessible
        abort_unless($sparePart->is_published, 404);

        $sparePart->load(['brand', 'creator', 'compatibleMotorcycles.brand']);

        // Related parts from the same category (excluding self)
        $related = SparePart::published()
                             ->available()
                             ->where('id', '!=', $sparePart->id)
                             ->where(function ($q) use ($sparePart) {
                                 $q->when($sparePart->category, fn ($q) => $q->orWhere('category', $sparePart->category))
                                   ->when($sparePart->brand_id, fn ($q) => $q->orWhere('brand_id', $sparePart->brand_id));
                             })
                             ->take(4)
                             ->get();

        return view('spare-parts.show', compact('sparePart', 'related'));
    }
}
