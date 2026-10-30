<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SparePart;
use App\Models\User;
use App\Support\OrderNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $query = Order::with(['items.motorcycle.brand', 'user']);
        } else {
            // Merchants see only their attributed items
            $query = Order::whereHas('items', function ($q) use ($user) {
                $q->where('merchant_id', $user->id);
            })->with(['items' => function ($q) use ($user) {
                $q->where('merchant_id', $user->id)->with('motorcycle.brand');
            }, 'user']);
        }

        // Filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_reference', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Stats for cards
        $stats = $this->getStats($user);

        return view('admin.sales.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $user = auth()->user();

        // Security: Merchants can only see orders they have items in
        if (! $user->isAdmin()) {
            $hasItem = $order->items()->where('merchant_id', $user->id)->exists();
            if (! $hasItem) {
                abort(403, 'Unauthorized access to this order.');
            }
            
            // Filter items for this merchant only
            $order->setRelation('items', $order->items()->where('merchant_id', $user->id)
                ->with(['motorcycle.brand', 'sparePart.brand', 'merchant'])->get());
        } else {
            $order->load(['items.motorcycle.brand', 'items.sparePart.brand', 'items.merchant', 'user', 'paymentVerifiedBy']);
        }

        return view('admin.sales.show', compact('order'));
    }

    public function updateItemStatus(Request $request, OrderItem $item)
    {
        $user = auth()->user();

        // Security: merchants can only update their own items
        if (! $user->isAdmin() && $item->merchant_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status'           => ['required', 'string', 'in:' . implode(',', array_keys(OrderItem::$merchantStatuses))],
            'fulfillment_notes'=> ['nullable', 'string', 'max:1000'],
            'rejection_reason' => ['required_if:status,rejected', 'nullable', 'string', 'max:500'],
            'tracking_number'  => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $item->transitionStatus(
                $validated['status'],
                $user->id,
                $validated['fulfillment_notes'] ?? null,
                $validated['rejection_reason'] ?? null
            );

            if (!empty($validated['tracking_number'])) {
                $item->update(['tracking_number' => $validated['tracking_number']]);
            }

            // Restore stock on rejection/cancellation of spare parts
            if (in_array($validated['status'], [OrderItem::STATUS_REJECTED, OrderItem::STATUS_CANCELLED])
                && $item->isSparePartItem()) {
                $sp = $item->sparePart;
                if ($sp) {
                    $sp->increment('stock_quantity', $item->quantity);
                    if ($sp->availability_status === \App\Models\SparePart::AVAIL_OUT_OF_STOCK) {
                        $sp->update(['availability_status' => \App\Models\SparePart::AVAIL_IN_STOCK]);
                    }
                }
            }

            // Notify the customer (and admins on rejection)
            OrderNotifier::notifyStatusChange($item);

        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Item status updated.');
    }

    public function verifyPayment(Request $request, Order $order)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'payment_status'    => ['required', 'in:pending,paid,failed,refunded'],
            'payment_reference' => ['required_if:payment_status,paid', 'nullable', 'string', 'max:100'],
            'admin_notes'       => ['nullable', 'string', 'max:2000'],
        ]);

        $updates = [
            'payment_status'    => $validated['payment_status'],
            'payment_reference' => $validated['payment_reference'] ?? null,
            'admin_notes'       => $validated['admin_notes'] ?? $order->admin_notes,
        ];

        if ($validated['payment_status'] === Order::PAY_PAID) {
            $updates['payment_verified_at'] = now();
            $updates['payment_verified_by'] = auth()->id();
            $updates['status'] = Order::STATUS_CONFIRMED;
        }

        $order->update($updates);

        return back()->with('success', 'Payment status updated.');
    }

    private function getStats(User $user)
    {
        if ($user->isAdmin()) {
            return [
                'total'     => Order::count(),
                'pending'   => Order::where('status', Order::STATUS_PENDING)->count(),
                'confirmed' => Order::where('status', Order::STATUS_CONFIRMED)->count(),
                'paid'      => Order::where('payment_status', Order::PAY_PAID)->count(),
                'completed' => Order::where('status', Order::STATUS_COMPLETED)->count(),
            ];
        }

        // For merchants, stats are based on their items
        $merchantItems = OrderItem::where('merchant_id', $user->id);
        
        return [
            'total'     => (clone $merchantItems)->count(),
            'pending'   => (clone $merchantItems)->where('status', 'pending')->count(),
            'confirmed' => (clone $merchantItems)->whereHas('order', fn($q) => $q->where('status', Order::STATUS_CONFIRMED))->count(),
            'paid'      => (clone $merchantItems)->whereHas('order', fn($q) => $q->where('payment_status', Order::PAY_PAID))->count(),
            'completed' => (clone $merchantItems)->where('status', 'completed')->count(),
        ];
    }
}
