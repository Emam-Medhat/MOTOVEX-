<?php

namespace App\Http\Controllers;

use App\Models\TestRideRequest;
use App\Http\Requests\StoreTestRideRequest;
use App\Models\Showroom;
use Illuminate\Http\Request;

/**
 * Handles the public Test Ride booking form.
 * Admin management lives in Admin\TestRideController.
 */
class TestRideController extends Controller
{
    public function store(StoreTestRideRequest $request)
    {
        $data = $request->validated();
        $data['ip_address'] = $request->ip();

        // Link to authenticated user if logged in
        if (auth()->check()) {
            $data['user_id'] = auth()->id();
            // Pre-fill name/email from profile if not provided
            $data['name']  = $data['name']  ?: auth()->user()->name;
            $data['email'] = $data['email'] ?: auth()->user()->email;
        }

        $booking = TestRideRequest::create($data);

        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'ok'        => true,
                'reference' => $booking->booking_reference,
                'message'   => 'Booking request received! Reference: ' . $booking->booking_reference . '. Our team will confirm your slot shortly.',
            ]);
        }

        return redirect()->back()->with('booking_success', [
            'reference' => $booking->booking_reference,
            'message'   => 'Your test ride request has been received.',
        ]);
    }
}
