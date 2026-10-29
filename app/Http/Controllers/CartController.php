<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // ─── View cart ────────────────────────────────────────────────────────────
    public function index()
    {
        $cartItems = $this->getCartWithModels();
        $total     = $this->calcTotal($cartItems);

        return view('cart.index', compact('cartItems', 'total'));
    }

    // ─── Add to cart ──────────────────────────────────────────────────────────
    public function add(Request $request, Motorcycle $motorcycle)
    {
        if (! $motorcycle->is_published) {
            return back()->with('error', 'This motorcycle is not available.');
        }

        if ($motorcycle->availability_status === 'sold') {
            return back()->with('error', $motorcycle->model_name . ' is no longer available.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$motorcycle->id])) {
            return back()->with('info', $motorcycle->model_name . ' is already in your cart.');
        }

        $cart[$motorcycle->id] = [
            'motorcycle_id' => $motorcycle->id,
            'added_at'      => now()->toDateTimeString(),
        ];

        session()->put('cart', $cart);

        return back()->with('success', $motorcycle->model_name . ' added to your cart!');
    }

    // ─── Remove from cart ─────────────────────────────────────────────────────
    public function remove(Motorcycle $motorcycle)
    {
        $cart = session()->get('cart', []);
        unset($cart[$motorcycle->id]);
        session()->put('cart', $cart);

        return back()->with('success', $motorcycle->model_name . ' removed from cart.');
    }

    // ─── Clear cart ───────────────────────────────────────────────────────────
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }

    // ─── Checkout page ────────────────────────────────────────────────────────
    public function checkout()
    {
        $cartItems = $this->getCartWithModels();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Remove any items that became unavailable since being added
        $unavailable = $cartItems->filter(
            fn ($item) => $item['motorcycle']->availability_status === 'sold'
                       || ! $item['motorcycle']->is_published
        );

        if ($unavailable->isNotEmpty()) {
            $cart = session()->get('cart', []);
            foreach ($unavailable as $item) {
                unset($cart[$item['motorcycle']->id]);
            }
            session()->put('cart', $cart);
            $cartItems = $this->getCartWithModels();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Some items in your cart became unavailable. Please browse for a new motorcycle.');
            }

            session()->flash('info', 'One or more unavailable motorcycles were removed from your cart.');
        }

        $total = $this->calcTotal($cartItems);

        return view('cart.checkout', compact('cartItems', 'total'));
    }

    // ─── Place order (submit checkout) ────────────────────────────────────────
    public function placeOrder(Request $request)
    {
        $cartItems = $this->getCartWithModels();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Server-side validation
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'max:150'],
            'phone'        => ['required', 'string', 'max:30'],
            'city'         => ['nullable', 'string', 'max:100'],
            'governorate'  => ['nullable', 'string', 'max:100'],
            'delivery_method' => ['nullable', 'string', 'in:delivery,showroom_collection'],
            'delivery_address' => ['nullable', 'string', 'max:400'],
            'payment_method'  => ['nullable', 'string', 'in:cash_on_delivery,bank_transfer,showroom'],
            'message'      => ['nullable', 'string', 'max:1000'],
        ]);

        // Idempotency guard — prevent duplicate submissions via double-click
        $submissionKey = 'order_submitted_' . md5(
            ($validated['email'] ?? '') . implode(',', $cartItems->keys()->toArray())
        );

        if (session()->has($submissionKey)) {
            return redirect()->route('home')
                ->with('success', 'Your order enquiry was already submitted successfully.');
        }

        $createdEnquiries = [];

        try {
            DB::transaction(function () use ($cartItems, $validated, $request, &$createdEnquiries) {
                foreach ($cartItems as $item) {
                    $moto = $item['motorcycle'];

                    // Re-fetch and lock for update (prevents race conditions)
                    $fresh = Motorcycle::lockForUpdate()->find($moto->id);

                    if (! $fresh || ! $fresh->is_published || $fresh->availability_status === 'sold') {
                        throw new \RuntimeException(
                            ($moto->model_name ?? 'A motorcycle') . ' is no longer available.'
                        );
                    }

                    // Server-side price — never trust client
                    $unitPrice    = (float) ($fresh->price ?? 0);
                    $deliveryFee  = 0.00;
                    $totalAmount  = $unitPrice + $deliveryFee;
                    $ref          = Enquiry::generateOrderReference();

                    $enquiry = Enquiry::create([
                        'user_id'             => auth()->id(),
                        'order_reference'     => $ref,
                        'name'                => $validated['name'],
                        'email'               => $validated['email'],
                        'phone'               => $validated['phone'],
                        'city'                => $validated['city'] ?? null,
                        'governorate'         => $validated['governorate'] ?? null,
                        'delivery_method'     => $validated['delivery_method'] ?? 'showroom_collection',
                        'delivery_address'    => $validated['delivery_address'] ?? null,
                        'payment_method'      => $validated['payment_method'] ?? 'cash_on_delivery',
                        'payment_status'      => Enquiry::PAY_PENDING,
                        'motorcycle_id'       => $fresh->id,
                        'motorcycle_snapshot' => $fresh->brand->name . ' ' . $fresh->model_name,
                        'unit_price'          => $unitPrice,
                        'currency'            => $fresh->currency ?? 'USD',
                        'quantity'            => 1,
                        'delivery_fee'        => $deliveryFee,
                        'total_amount'        => $totalAmount,
                        'subject'             => 'Purchase Order: ' . $fresh->brand->name . ' ' . $fresh->model_name,
                        'message'             => $validated['message'] ?? '',
                        'enquiry_type'        => Enquiry::TYPE_PURCHASE,
                        'status'              => Enquiry::STATUS_PENDING,
                    ]);

                    $createdEnquiries[] = $enquiry;
                }
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')
                ->with('error', $e->getMessage());
        }

        // Clear cart only after all enquiries created successfully
        session()->forget('cart');

        // Set idempotency guard (expires with session)
        session()->put($submissionKey, true);

        // Pass first order reference to success view
        $firstRef = $createdEnquiries[0]->order_reference ?? null;

        return redirect()->route('cart.order-success')
            ->with('order_reference', $firstRef)
            ->with('order_count', count($createdEnquiries));
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

    // ─── Helper: recalculate total server-side ────────────────────────────────
    private function calcTotal(\Illuminate\Support\Collection $cartItems): float
    {
        return (float) $cartItems->sum(fn ($item) => $item['motorcycle']->price ?? 0);
    }

    // ─── Helper: resolve cart session to models ───────────────────────────────
    private function getCartWithModels(): \Illuminate\Support\Collection
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return collect();
        }

        $ids         = array_keys($cart);
        $motorcycles = Motorcycle::with('brand')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return collect($cart)->map(function ($item) use ($motorcycles) {
            $moto = $motorcycles->get($item['motorcycle_id']);
            if (! $moto) return null;

            return [
                'motorcycle' => $moto,
                'added_at'   => $item['added_at'],
            ];
        })->filter();
    }
}
