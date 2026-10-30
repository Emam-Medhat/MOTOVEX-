<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Support\OrderNotifier;
use Illuminate\Http\Request;

/**
 * Merchant order management — spare parts fulfillment.
 * Merchants see ONLY their own order items.
 */
class OrderController extends Controller
{
    private function requireApprovedMerchant(): void
    {
        abort_unless(auth()->user()->isApprovedMerchant(), 403,
            'Only approved merchants can manage orders.');
    }

    // ── Merchant order item listing ───────────────────────────────────────────
    public function index(Request $request)
    {
        $this->requireApprovedMerchant();
        $user = auth()->user();

        $query = OrderItem::with(['order', 'sparePart'])
            ->forMerchant($user)
            ->spareParts()
            ->latest('order_items.created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('product_snapshot', 'like', "%{$s}%")
                  ->orWhereHas('order', fn ($oq) => $oq->where('order_reference', 'like', "%{$s}%"));
            });
        }

        $items = $query->paginate(15)->withQueryString();

        // Stats for this merchant
        $base = OrderItem::forMerchant($user)->spareParts();
        $stats = [
            'total'     => (clone $base)->count(),
            'pending'   => (clone $base)->where('status', OrderItem::STATUS_PENDING)->count(),
            'accepted'  => (clone $base)->where('status', OrderItem::STATUS_ACCEPTED)->count(),
            'shipped'   => (clone $base)->where('status', OrderItem::STATUS_SHIPPED)->count(),
            'delivered' => (clone $base)->where('status', OrderItem::STATUS_DELIVERED)->count(),
        ];

        $statuses = OrderItem::$merchantStatuses;

        return view('account.merchant-orders.index', compact('items', 'stats', 'statuses'));
    }

    // ── Single order item detail ──────────────────────────────────────────────
    public function show(OrderItem $item)
    {
        $this->requireApprovedMerchant();

        // Merchant can only see their own items
        abort_unless($item->merchant_id === auth()->id(), 403);
        abort_unless($item->isSparePartItem(), 404);

        $item->load(['order', 'sparePart.brand', 'statusUpdatedBy']);

        $allowedTransitions = OrderItem::$transitions[$item->status] ?? [];

        return view('account.merchant-orders.show', compact('item', 'allowedTransitions'));
    }

    // ── Update fulfillment status ─────────────────────────────────────────────
    public function updateStatus(Request $request, OrderItem $item)
    {
        $this->requireApprovedMerchant();

        // Ownership check — server-side
        abort_unless($item->merchant_id === auth()->id(), 403, 'You do not own this order item.');
        abort_unless($item->isSparePartItem(), 403);

        $validated = $request->validate([
            'status'           => ['required', 'string', 'in:' . implode(',', array_keys(OrderItem::$merchantStatuses))],
            'fulfillment_notes'=> ['nullable', 'string', 'max:1000'],
            'rejection_reason' => ['nullable', 'string', 'max:500'],
            'tracking_number'  => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $item->transitionStatus(
                $validated['status'],
                auth()->id(),
                $validated['fulfillment_notes'] ?? null,
                $validated['rejection_reason'] ?? null
            );

            // Save tracking number if provided
            if (! empty($validated['tracking_number'])) {
                $item->update(['tracking_number' => $validated['tracking_number']]);
            }

            // Restore stock if rejected/cancelled
            if (in_array($validated['status'], [OrderItem::STATUS_REJECTED, OrderItem::STATUS_CANCELLED])) {
                $this->restoreStock($item);
            }

            // Notify the customer (and admins on rejection)
            OrderNotifier::notifyStatusChange($item);

        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Status updated to: ' . (OrderItem::$merchantStatuses[$validated['status']] ?? $validated['status']));
    }

    // ── Restore stock on rejection/cancellation ───────────────────────────────
    private function restoreStock(OrderItem $item): void
    {
        if (! $item->isSparePartItem() || ! $item->spare_part_id) {
            return;
        }

        $sparePart = $item->sparePart;
        if (! $sparePart) return;

        $sparePart->increment('stock_quantity', $item->quantity);

        // Reset availability if it was out_of_stock
        if ($sparePart->availability_status === \App\Models\SparePart::AVAIL_OUT_OF_STOCK) {
            $sparePart->update(['availability_status' => \App\Models\SparePart::AVAIL_IN_STOCK]);
        }
    }
}
