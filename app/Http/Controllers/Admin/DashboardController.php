<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Enquiry;
use App\Models\Motorcycle;
use App\Models\Promotion;
use App\Models\Showroom;
use App\Models\TestRideRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $motorcycleQuery = Motorcycle::query();
        $enquiryQuery = Enquiry::query();
        $testRideQuery = TestRideRequest::query();
        $orderQuery = Order::query();
        $orderItemQuery = OrderItem::query();

        if (! $user->isAdmin()) {
            $motorcycleQuery->visibleToManager($user);
            $enquiryQuery->whereHas('motorcycle', fn ($query) => $query->responsibleFor($user));
            $testRideQuery->whereHas('motorcycle', fn ($query) => $query->responsibleFor($user));
            $orderQuery->whereHas('items', fn($q) => $q->where('merchant_id', $user->id));
            $orderItemQuery->where('merchant_id', $user->id);
        }

        $recentMotorcycles = (clone $motorcycleQuery)->with('brand')->latest()->take(5)->get();
        $recentOrders = (clone $orderQuery)->latest()->take(5)->get();
        $recentEnquiries = (clone $enquiryQuery)->with('motorcycle.brand')->latest()->take(5)->get();
        
        $recentActivity = $recentOrders->map(fn (Order $order) => [
            'reference' => $order->order_reference,
            'type' => 'Sale Order',
            'customer' => $order->name,
            'motorcycle' => $order->items->count() . ' item(s)',
            'status' => $order->status,
            'created_at' => $order->created_at,
            'url' => route('admin.sales.show', $order),
        ])->sortByDesc('created_at')->take(8)->values();

        $requestCount = fn (string $type, ?string $status = null) => (clone $enquiryQuery)
            ->where('enquiry_type', $type)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->count();
            
        $salesCount = fn (?string $status = null) => $user->isAdmin() 
            ? Order::when($status, fn($q) => $q->where('status', $status))->count()
            : OrderItem::where('merchant_id', $user->id)->when($status, fn($q) => $q->where('status', $status))->count();

        $rideCount = fn (?string $status = null) => (clone $testRideQuery)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->count();
            
        $pendingCount = ($user->isAdmin() ? Order::where('status', Order::STATUS_PENDING)->count() : OrderItem::where('merchant_id', $user->id)->where('status', 'pending')->count())
            + (clone $enquiryQuery)->where('status', Enquiry::STATUS_PENDING)->count()
            + (clone $testRideQuery)->where('status', TestRideRequest::STATUS_PENDING)->count();

        if ($user->isAdmin()) {
            $statCards = [
                ['Brands', Brand::count(), 'text-[#a3e635]'],
                ['Motorcycles', (clone $motorcycleQuery)->count(), 'text-blue-400'],
                ['Sales Orders', Order::count(), 'text-purple-400'],
                ['Pending Sales', Order::where('status', Order::STATUS_PENDING)->count(), 'text-yellow-400'],
                ['Confirmed Sales', Order::where('status', Order::STATUS_CONFIRMED)->count(), 'text-green-400'],
                ['Total Enquiries', (clone $enquiryQuery)->count(), 'text-blue-400'],
                ['Test-Ride Bookings', $rideCount(), 'text-blue-400'],
                ['Pending Actions', $pendingCount, 'text-yellow-400'],
            ];
        } elseif ($user->isDataEntry()) {
            $statCards = [
                ['My Motorcycles', (clone $motorcycleQuery)->count(), 'text-blue-400'],
                ['My Sales', OrderItem::where('merchant_id', $user->id)->count(), 'text-purple-400'],
                ['New Orders', OrderItem::where('merchant_id', $user->id)->where('status', 'pending')->count(), 'text-yellow-400'],
                ['Confirmed Sales', OrderItem::where('merchant_id', $user->id)->whereHas('order', fn($q) => $q->where('status', Order::STATUS_CONFIRMED))->count(), 'text-green-400'],
                ['My Enquiries', (clone $enquiryQuery)->count(), 'text-blue-400'],
                ['Test-Ride Bookings', $rideCount(), 'text-blue-400'],
                ['Pending Actions', $pendingCount, 'text-yellow-400'],
            ];
        } else {
            $statCards = [
                ['My Motorcycles', (clone $motorcycleQuery)->count(), 'text-blue-400'],
                ['Enquiries', (clone $enquiryQuery)->count(), 'text-purple-400'],
                ['Test-Ride Bookings', $rideCount(), 'text-orange-400'],
                ['Pending Actions', $pendingCount, 'text-yellow-400'],
            ];
        }

        $canSeeEnquiries = $user->isAdmin() || $user->isSalesAgent() || $user->isDataEntry();

        return view('admin.dashboard', compact(
            'statCards',
            'recentEnquiries',
            'recentMotorcycles',
            'recentActivity',
            'canSeeEnquiries'
        ));
    }
}
