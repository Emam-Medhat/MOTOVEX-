<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // ─── View cart ────────────────────────────────────────────────────────────
    public function index()
    {
        $cartItems = $this->getCartWithModels();

        $total = $cartItems->sum(fn ($item) => $item['motorcycle']->price ?? 0);

        return view('cart.index', compact('cartItems', 'total'));
    }

    // ─── Add to cart ──────────────────────────────────────────────────────────
    public function add(Request $request, Motorcycle $motorcycle)
    {
        if (! $motorcycle->is_published) {
            return back()->with('error', 'This motorcycle is not available.');
        }

        $cart = session()->get('cart', []);

        // Only one unit per motorcycle (it's a vehicle, not a commodity)
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

        $total = $cartItems->sum(fn ($item) => $item['motorcycle']->price ?? 0);

        return view('cart.checkout', compact('cartItems', 'total'));
    }

    // ─── Place order (submit checkout) ────────────────────────────────────────
    public function placeOrder(Request $request)
    {
        $cartItems = $this->getCartWithModels();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'phone'   => ['required', 'string', 'max:30'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        // Build one enquiry per motorcycle in the cart
        foreach ($cartItems as $item) {
            $moto = $item['motorcycle'];

            \App\Models\Enquiry::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'phone'        => $request->phone,
                'subject'      => 'Purchase Enquiry: ' . $moto->brand->name . ' ' . $moto->model_name,
                'message'      => $request->filled('message')
                    ? $request->message
                    : 'Customer submitted a purchase enquiry via the cart checkout.',
                'enquiry_type' => 'purchase',
                'status'       => 'pending',
            ]);
        }

        session()->forget('cart');

        return redirect()->route('home')
            ->with('success', 'Your order enquiry has been submitted! Our team will contact you shortly.');
    }

    // ─── Helper ───────────────────────────────────────────────────────────────
    private function getCartWithModels(): \Illuminate\Support\Collection
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return collect();
        }

        $ids = array_keys($cart);
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
