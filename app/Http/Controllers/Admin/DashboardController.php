<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Enquiry;
use App\Models\Motorcycle;
use App\Models\User;
use App\Models\Promotion;
use App\Models\Showroom;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $recentEnquiries = collect();
        $recentMotorcycles = collect();

        if ($user->isAdmin()) {
            $statCards = [
                ['Brands',      Brand::count(),                        'text-[#a3e635]'],
                ['Motorcycles', Motorcycle::count(),                   'text-blue-400'],
                ['Promotions',  Promotion::count(),                    'text-yellow-400'],
                ['Enquiries',   Enquiry::count(),                      'text-purple-400'],
                ['Test Rides',  \App\Models\TestRideRequest::count(),  'text-orange-400'],
            ];

            $recentEnquiries   = Enquiry::latest()->take(5)->get();
            $recentMotorcycles = Motorcycle::with('brand')->latest()->take(5)->get();
        } elseif ($user->isSalesAgent()) {
            $statCards = [
                ['Total Enquiries', Enquiry::count(),                                      'text-[#a3e635]'],
                ['Pending',         Enquiry::where('status', 'pending')->count(),          'text-yellow-400'],
                ['Test Rides',      \App\Models\TestRideRequest::count(),                  'text-blue-400'],
                ['Completed',       Enquiry::where('status', 'completed')->count(),        'text-green-400'],
            ];

            $recentEnquiries = Enquiry::latest()->take(5)->get();
        } else {
            $brandIds = $user->assignedBrands()->pluck('brands.id');

            $statCards = [
                ['My Brands',   $brandIds->count(),                                                            'text-[#a3e635]'],
                ['Motorcycles', Motorcycle::whereIn('brand_id', $brandIds)->count(),                            'text-blue-400'],
                ['Promotions',  Promotion::whereIn('brand_id', $brandIds)->count(),                             'text-yellow-400'],
                ['Showrooms',   Showroom::whereIn('brand_id', $brandIds)->count(),                              'text-purple-400'],
            ];

            $recentMotorcycles = Motorcycle::with('brand')
                ->whereIn('brand_id', $brandIds)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('admin.dashboard', compact('statCards', 'recentEnquiries', 'recentMotorcycles'));
    }
}
