<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Motorcycle;
use App\Models\SparePart;
use App\Support\OrderNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Handles the session-based shopping cart for both Motorcycles and Spare Parts.
 *
 * Cart session structure:
 *   cart = [
 *     'motorcycles' => [ motorcycle_id => ['motorcycle_id' => X, 'added_at' => '...'], ... ],
 *     'spare_parts' => [ spare_part_id => ['spare_part_id' => X, 'quantity' => N, 'added_at' => '...'], ... ],
 *   ]
 */
class CartController extends Controller
{
    // ─── View cart ────────────────────────────────────────────────────────────
    public function index()
    {
        ['motorcycles' => $motoItems, 'spare_parts' => $spItems] = $this->getCartWithModels();
        $total = $this->calcTotal($motoItems, $spItems);

        return view('cart.index', compact('motoItems', 'spItems', 'total'));
    }

    // ─── Add motorcycle to cart ───────────────────────────────────────────────
    public function add(Request $request, Motorcycle $motorcycle)
    {
        if (! $motorcycle->is_published) {
            return back()->with('error', 'This motorcycle is not available.');
        }

        if ($motorcycle->availability_status === 'sold') {
            return back()->with('error', $motorcycle->model_name . ' is no longer available.');
        }

        $cart = session()->get('cart', $this->emptyCart());
        $this->ensureToken($cart);

        if (isset($cart['motorcycles'][$motorcycle->id])) {
            return back()->with('info', $motorcycle->model_name . ' is already in your cart.');
        }

        $cart['motorcycles'][$motorcycle->id] = [
            'motorcycle_id' => $motorcycle->id,
            'added_at'      => now()->toDateTimeString(),
        ];

        session()->put('cart', $cart);

        return back()->with('success', $motorcycle->model_name . ' added to your cart!');
    }

    // ─── Add spare part to cart ───────────────────────────────────────────────
    public function addSparePart(Request $request, SparePart $sparePart)
    {
        if (! $sparePart->is_published) {
            return back()->with('error', 'This spare part is not available.');
        }

        if (! $sparePart->isAvailable()) {
            return back()->with('error', $sparePart->name . ' is out of stock.');
        }

        $qty = (int) $request->input('quantity', 1);
        if ($qty < 1) $qty = 1;

        // Validate against available stock (server-side)
        if ($sparePart->stock_quantity > 0 && $qty > $sparePart->stock_quantity) {
            $qty = $sparePart->stock_quantity;
        }

        $cart = session()->get('cart', $this->emptyCart());
        $this->ensureToken($cart);

        $existing = $cart['spare_parts'][$sparePart->id]['quantity'] ?? 0;
        $newQty   = $existing + $qty;

        // Cap at stock
        if ($sparePart->stock_quantity > 0 && $newQty > $sparePart->stock_quantity) {
            $newQty = $sparePart->stock_quantity;
        }

        $cart['spare_parts'][$sparePart->id] = [
            'spare_part_id' => $sparePart->id,
            'quantity'      => $newQty,
            'added_at'      => $cart['spare_parts'][$sparePart->id]['added_at'] ?? now()->toDateTimeString(),
        ];

        session()->put('cart', $cart);

        return back()->with('success', $sparePart->name . ' added to your cart!');
    }

    // ─── Update spare part quantity ───────────────────────────────────────────
    public function updateSparePart(Request $request, SparePart $sparePart)
    {
        $qty  = (int) $request->input('quantity', 1);
        $cart = session()->get('cart', $this->emptyCart());

        if (! isset($cart['spare_parts'][$sparePart->id])) {
            return back()->with('error', 'Item not in cart.');
        }

        if ($qty <= 0) {
            unset($cart['spare_parts'][$sparePart->id]);
            session()->put('cart', $cart);
            return back()->with('success', $sparePart->name . ' removed from cart.');
        }

        // Validate stock server-side
        $fresh = SparePart::find($sparePart->id);
        if ($fresh && $fresh->stock_quantity > 0 && $qty > $fresh->stock_quantity) {
            $qty = $fresh->stock_quantity;
            session()->flash('info', 'Quantity adjusted to available stock (' . $qty . ').');
        }

        $cart['spare_parts'][$sparePart->id]['quantity'] = $qty;
        session()->put('cart', $cart);

        return back()->with('success', 'Quantity updated.');
    }

    // ─── Remove motorcycle from cart ──────────────────────────────────────────
    public function remove(Motorcycle $motorcycle)
    {
        $cart = session()->get('cart', $this->emptyCart());
        unset($cart['motorcycles'][$motorcycle->id]);
        session()->put('cart', $cart);

        return back()->with('success', $motorcycle->model_name . ' removed from cart.');
    }

    // ─── Remove spare part from cart ──────────────────────────────────────────
    public function removeSparePart(SparePart $sparePart)
    {
        $cart = session()->get('cart', $this->emptyCart());
        unset($cart['spare_parts'][$sparePart->id]);
        session()->put('cart', $cart);

        return back()->with('success', $sparePart->name . ' removed from cart.');
    }

    // ─── Clear entire cart ────────────────────────────────────────────────────
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }

    // ─── Checkout page ────────────────────────────────────────────────────────
    public function checkout()
    {
        ['motorcycles' => $motoItems, 'spare_parts' => $spItems] = $this->getCartWithModels();

        if ($motoItems->isEmpty() && $spItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Remove unavailable motorcycles
        $unavailableMotos = $motoItems->filter(
            fn ($item) => $item['motorcycle']->availability_status === 'sold' || ! $item['motorcycle']->is_published
        );

        // Remove unavailable spare parts
        $unavailableSp = $spItems->filter(
            fn ($item) => ! $item['spare_part']->is_published || ! $item['spare_part']->isAvailable()
        );

        if ($unavailableMotos->isNotEmpty() || $unavailableSp->isNotEmpty()) {
            $cart = session()->get('cart', $this->emptyCart());

            foreach ($unavailableMotos as $item) {
                unset($cart['motorcycles'][$item['motorcycle']->id]);
            }
            foreach ($unavailableSp as $item) {
                unset($cart['spare_parts'][$item['spare_part']->id]);
            }

            session()->put('cart', $cart);
            ['motorcycles' => $motoItems, 'spare_parts' => $spItems] = $this->getCartWithModels();

            if ($motoItems->isEmpty() && $spItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'All items in your cart became unavailable. Please continue shopping.');
            }

            session()->flash('info', 'Some unavailable items were removed from your cart.');
        }

        $total = $this->calcTotal($motoItems, $spItems);

        // Regenerate submission token
        session()->put('cart_submission_token', (string) Str::uuid());

        return view('cart.checkout', compact('motoItems', 'spItems', 'total'));
    }

    // ─── Place order (checkout submit) ────────────────────────────────────────
    public function placeOrder(Request $request)
    {
        ['motorcycles' => $motoItems, 'spare_parts' => $spItems] = $this->getCartWithModels();

        if ($motoItems->isEmpty() && $spItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'email'            => ['required', 'email', 'max:150'],
            'phone'            => ['required', 'string', 'max:30'],
            'city'             => ['nullable', 'string', 'max:100'],
            'governorate'      => ['nullable', 'string', 'max:100'],
            'delivery_method'  => ['nullable', 'string', 'in:delivery,showroom_collection'],
            'delivery_address' => ['nullable', 'string', 'max:400'],
            'payment_method'   => ['nullable', 'string', 'in:cash_on_delivery,bank_transfer,showroom'],
            'message'          => ['nullable', 'string', 'max:1000'],
            'idempotency_key'  => ['nullable', 'string'],
        ]);

        // ── Idempotency ────────────────────────────────────────────────────────
        $submittedKey = trim($validated['idempotency_key'] ?? '');
        $sessionToken = session('cart_submission_token', '');

        if ($submittedKey !== '' && $sessionToken !== '' && hash_equals($sessionToken, $submittedKey)) {
            $idempotencyKey = $submittedKey;
        } else {
            $idempotencyKey = (string) Str::uuid();
        }

        if ($submittedKey !== '') {
            $existing = Order::where('idempotency_key', $submittedKey)->first();
            if ($existing) {
                session()->forget(['cart', 'cart_submission_token']);
                return redirect()->route('cart.order-success')
                    ->with('order_reference', $existing->order_reference)
                    ->with('order_count', $existing->items()->count());
            }
        }

        $order = null;

        try {
            DB::transaction(function () use ($motoItems, $spItems, $validated, $idempotencyKey, &$order) {
                $totalAmount = $this->calcTotal($motoItems, $spItems);

                // Determine currency from first item
                $currency = 'USD';
                if ($motoItems->isNotEmpty()) {
                    $currency = $motoItems->first()['motorcycle']->currency ?? 'USD';
                } elseif ($spItems->isNotEmpty()) {
                    $currency = $spItems->first()['spare_part']->currency ?? 'USD';
                }

                $order = Order::create([
                    'user_id'          => auth()->id(),
                    'order_reference'  => Order::generateReference(),
                    'name'             => $validated['name'],
                    'email'            => $validated['email'],
                    'phone'            => $validated['phone'],
                    'city'             => $validated['city'] ?? null,
                    'governorate'      => $validated['governorate'] ?? null,
                    'delivery_method'  => $validated['delivery_method'] ?? 'showroom_collection',
                    'delivery_address' => $validated['delivery_address'] ?? null,
                    'total_amount'     => $totalAmount,
                    'currency'         => $currency,
                    'payment_method'   => $validated['payment_method'] ?? 'cash_on_delivery',
                    'payment_status'   => Order::PAY_PENDING,
                    'status'           => Order::STATUS_PENDING,
                    'admin_notes'      => $validated['message'] ?? null,
                    'idempotency_key'  => $idempotencyKey,
                ]);

                // ── Process motorcycle items ───────────────────────────────────
                foreach ($motoItems as $item) {
                    $moto  = $item['motorcycle'];
                    $fresh = Motorcycle::lockForUpdate()->find($moto->id);

                    if (! $fresh || ! $fresh->is_published || $fresh->availability_status === 'sold') {
                        throw new \RuntimeException(
                            ($moto->model_name ?? 'A motorcycle') . ' is no longer available.'
                        );
                    }

                    $unitPrice       = (float) ($fresh->price ?? 0);
                    $productSnapshot = ($fresh->brand->name ?? '') . ' ' . $fresh->model_name;

                    OrderItem::create([
                        'order_id'            => $order->id,
                        'product_type'        => OrderItem::TYPE_MOTORCYCLE,
                        'motorcycle_id'       => $fresh->id,
                        'merchant_id'         => $fresh->responsibleManagerId() ?? auth()->id(),
                        'motorcycle_snapshot' => $productSnapshot,
                        'product_snapshot'    => $productSnapshot,
                        'unit_price'          => $unitPrice,
                        'quantity'            => 1,
                        'subtotal'            => $unitPrice,
                        'status'              => OrderItem::STATUS_PENDING,
                    ]);
                }

                // ── Process spare part items ──────────────────────────────────
                foreach ($spItems as $item) {
                    $sp       = $item['spare_part'];
                    $qty      = (int) $item['quantity'];
                    $fresh    = SparePart::lockForUpdate()->find($sp->id);

                    if (! $fresh || ! $fresh->is_published || ! $fresh->isAvailable()) {
                        throw new \RuntimeException(
                            ($sp->name ?? 'A spare part') . ' is no longer available.'
                        );
                    }

                    // Stock validation
                    if ($fresh->stock_quantity > 0 && $qty > $fresh->stock_quantity) {
                        throw new \RuntimeException(
                            'Insufficient stock for "' . $fresh->name . '". ' .
                            'Available: ' . $fresh->stock_quantity . ', requested: ' . $qty . '.'
                        );
                    }

                    $unitPrice = (float) ($fresh->price ?? 0);

                    OrderItem::create([
                        'order_id'            => $order->id,
                        'product_type'        => OrderItem::TYPE_SPARE_PART,
                        'spare_part_id'       => $fresh->id,
                        'merchant_id'         => $fresh->created_by,  // always server-side
                        'motorcycle_snapshot' => $fresh->name,        // compat
                        'product_snapshot'    => $fresh->name,
                        'sku_snapshot'        => $fresh->sku,
                        'unit_price'          => $unitPrice,
                        'quantity'            => $qty,
                        'subtotal'            => $unitPrice * $qty,
                        'status'              => OrderItem::STATUS_PENDING,
                    ]);

                    // Decrement stock atomically — exactly once, inside the transaction
                    if ($fresh->stock_quantity > 0) {
                        $fresh->decrement('stock_quantity', $qty);

                        // Update availability_status automatically
                        $fresh->refresh();
                        if ($fresh->stock_quantity === 0) {
                            $fresh->update(['availability_status' => SparePart::AVAIL_OUT_OF_STOCK]);
                        } elseif ($fresh->stock_quantity <= 5) {
                            $fresh->update(['availability_status' => SparePart::AVAIL_LOW_STOCK]);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Checkout failed: ' . $e->getMessage());
        }

        session()->forget(['cart', 'cart_submission_token']);

        // Route every purchased item to its responsible merchant (notifications)
        OrderNotifier::notifyMerchants($order);

        return redirect()->route('cart.order-success')
            ->with('order_reference', $order->order_reference)
            ->with('order_count', $order->items()->count());
    }

    // ─── Order success page ───────────────────────────────────────────────────
    public function orderSuccess()
    {
        if (! session()->has('order_reference')) {
            return redirect()->route('home');
        }

        $reference  = session('order_reference');
        $orderCount = session('order_count', 1);

        return view('cart.order-success', compact('reference', 'orderCount'));
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    private function emptyCart(): array
    {
        return ['motorcycles' => [], 'spare_parts' => []];
    }

    private function ensureToken(array &$cart): void
    {
        $isEmpty = empty($cart['motorcycles']) && empty($cart['spare_parts']);
        if ($isEmpty) {
            session()->put('cart_submission_token', (string) Str::uuid());
        }
    }

    private function calcTotal(\Illuminate\Support\Collection $motoItems, \Illuminate\Support\Collection $spItems): float
    {
        $motoTotal = (float) $motoItems->sum(fn ($item) => $item['motorcycle']->price ?? 0);
        $spTotal   = (float) $spItems->sum(fn ($item) => ((float) ($item['spare_part']->price ?? 0)) * (int) $item['quantity']);

        return $motoTotal + $spTotal;
    }

    /** Resolve both halves of the cart from session to models. */
    private function getCartWithModels(): array
    {
        $cart = session()->get('cart', $this->emptyCart());

        // Backwards-compat: old cart structure was flat motorcycle-only
        if (isset($cart['motorcycle_id'])) {
            $cart = $this->migrateLegacyCart($cart);
            session()->put('cart', $cart);
        }

        // ── Motorcycles ────────────────────────────────────────────────────
        $motoIds     = array_keys($cart['motorcycles'] ?? []);
        $motorcycles = empty($motoIds) ? collect() : Motorcycle::with('brand')
            ->whereIn('id', $motoIds)
            ->get()
            ->keyBy('id');

        $motoItems = collect($cart['motorcycles'] ?? [])->map(function ($item) use ($motorcycles) {
            $moto = $motorcycles->get($item['motorcycle_id'] ?? null);
            return $moto ? ['motorcycle' => $moto, 'added_at' => $item['added_at']] : null;
        })->filter()->values();

        // ── Spare parts ────────────────────────────────────────────────────
        $spIds      = array_keys($cart['spare_parts'] ?? []);
        $spareParts = empty($spIds) ? collect() : SparePart::with('brand')
            ->whereIn('id', $spIds)
            ->get()
            ->keyBy('id');

        $spItems = collect($cart['spare_parts'] ?? [])->map(function ($item) use ($spareParts) {
            $sp = $spareParts->get($item['spare_part_id'] ?? null);
            return $sp ? [
                'spare_part' => $sp,
                'quantity'   => max(1, (int) ($item['quantity'] ?? 1)),
                'added_at'   => $item['added_at'],
            ] : null;
        })->filter()->values();

        return ['motorcycles' => $motoItems, 'spare_parts' => $spItems];
    }

    /** Convert old flat motorcycle-only cart to the new structured format. */
    private function migrateLegacyCart(array $flatCart): array
    {
        $newCart = $this->emptyCart();
        foreach ($flatCart as $key => $item) {
            if (is_array($item) && isset($item['motorcycle_id'])) {
                $newCart['motorcycles'][$item['motorcycle_id']] = $item;
            }
        }
        return $newCart;
    }
}
