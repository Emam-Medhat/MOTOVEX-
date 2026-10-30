<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Enquiry;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Use user_id when available, fall back to email match for legacy records
        $baseQuery = Enquiry::where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('email', $user->email);
        });

        $enquiries = (clone $baseQuery)
            ->where('enquiry_type', '!=', Enquiry::TYPE_PURCHASE)
            ->latest()->take(5)->get();

        // Canonical orders created through the cart / checkout
        $orderQuery = $this->customerOrderQuery($user);

        $recentOrders = (clone $orderQuery)->withCount('items')->latest()->take(3)->get();

        $stats = [
            'total'      => (clone $baseQuery)->where('enquiry_type', '!=', Enquiry::TYPE_PURCHASE)->count(),
            'open'       => (clone $baseQuery)->where('enquiry_type', '!=', Enquiry::TYPE_PURCHASE)->where('status', 'pending')->count(),
            'test_rides' => (clone $baseQuery)->where('enquiry_type', Enquiry::TYPE_TEST_DRIVE)->count(),
            'orders'     => (clone $orderQuery)->count(),
        ];

        return view('account.dashboard', compact('user', 'enquiries', 'recentOrders', 'stats'));
    }

    /**
     * Orders belonging to the authenticated customer.
     * Ownership is enforced server-side (never by URL alone).
     */
    private function customerOrderQuery($user)
    {
        return Order::query()->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('email', $user->email);
        });
    }

    public function orders(Request $request)
    {
        $user = auth()->user();

        $query = $this->customerOrderQuery($user)->withCount('items');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where('order_reference', 'like', "%{$s}%");
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        $base = $this->customerOrderQuery($user);
        $stats = [
            'total'     => (clone $base)->count(),
            'pending'   => (clone $base)->where('status', Order::STATUS_PENDING)->count(),
            'processing'=> (clone $base)->whereIn('status', [Order::STATUS_CONFIRMED, Order::STATUS_PROCESSING])->count(),
            'completed' => (clone $base)->where('status', Order::STATUS_COMPLETED)->count(),
        ];

        return view('account.orders', compact('orders', 'stats'));
    }

    public function orderShow(Order $order)
    {
        $user = auth()->user();

        // Ownership check — customer can only see their own orders
        abort_unless(
            $order->user_id === $user->id || $order->email === $user->email,
            403,
            'You do not have permission to view this order.'
        );

        $order->load([
            'items.sparePart.brand',
            'items.motorcycle.brand',
            'items.merchant',
            'items.statusUpdatedBy',
        ]);

        return view('account.order-show', compact('order'));
    }

    public function enquiries()
    {
        $user = auth()->user();

        $enquiries = Enquiry::where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('email', $user->email);
            })
            ->where('enquiry_type', '!=', Enquiry::TYPE_PURCHASE)
            ->latest()
            ->paginate(10);

        return view('account.enquiries', compact('enquiries'));
    }

    public function editProfile()
    {
        $brands = Brand::published()->orderBy('name')->get();

        return view('account.profile', compact('brands'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'               => ['required', 'string', 'max:100'],
            'phone'              => ['nullable', 'string', 'max:30'],
            'country'            => ['nullable', 'string', 'max:100'],
            'city'               => ['nullable', 'string', 'max:100'],
            'preferred_brand_id' => ['nullable', 'exists:brands,id'],
            'newsletter'         => ['nullable', 'boolean'],
        ]);

        $user->update([
            'name'                => $data['name'],
            'phone'               => $data['phone'] ?? null,
            'country'             => $data['country'] ?? null,
            'city'                => $data['city'] ?? null,
            'preferred_brand_id'  => $data['preferred_brand_id'] ?? null,
            'newsletter_opted_in' => $request->boolean('newsletter'),
        ]);

        return redirect()->route('account.profile')->with('success', 'Your profile has been updated.');
    }

    public function editSecurity()
    {
        return view('account.security');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('account.security')->with('success', 'Your password has been changed.');
    }
}
