<?php

namespace App\Http\Controllers;

use App\Models\Promotion;

class PromotionController extends Controller
{
    /**
     * Public promotions listing.
     *
     * Only live promotions (active, started, not expired) are ever exposed.
     * Discount values are never calculated here beyond the stored business
     * data on the promotion + linked motorcycle.
     */
    public function index()
    {
        $promotions = Promotion::with(['brand', 'motorcycle.brand'])
            ->live()
            ->orderByDesc('created_at')
            ->get();

        return view('promotions', compact('promotions'));
    }
}
