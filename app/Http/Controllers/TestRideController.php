<?php

namespace App\Http\Controllers;

use App\Models\TestRideRequest;
use App\Http\Requests\StoreTestRideRequest;
use App\Models\Showroom;
use App\Models\Motorcycle;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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

        $booking = DB::transaction(function () use ($data) {
            if (! empty($data['motorcycle_id'])) {
                $motorcycle = Motorcycle::lockForUpdate()->findOrFail($data['motorcycle_id']);
                abort_unless($motorcycle->is_published && $motorcycle->availability_status !== 'sold', 422, 'This motorcycle is not available.');

                $conflict = TestRideRequest::where('motorcycle_id', $motorcycle->id)
                    ->whereDate('preferred_date', $data['preferred_date'])
                    ->where('preferred_time', $data['preferred_time'])
                    ->whereIn('status', [TestRideRequest::STATUS_PENDING, TestRideRequest::STATUS_CONFIRMED])
                    ->exists();
                if ($conflict) {
                    throw ValidationException::withMessages([
                        'preferred_time' => 'This motorcycle already has a booking at that time.',
                    ]);
                }
            }

            return TestRideRequest::create($data);
        });

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
