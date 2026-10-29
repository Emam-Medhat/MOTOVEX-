<?php

namespace App\Http\Controllers;

use App\Models\TestRideRequest;
use App\Http\Requests\StoreTestRideRequest;
use Illuminate\Http\Request;

/**
 * Handles the homepage test-ride mini-form.
 * CSRF is handled by Laravel; basic rate limiting is applied at the route.
 */
class TestRideController extends Controller
{
    public function store(StoreTestRideRequest $request)
    {
        $data = $request->validated();
        $data['ip_address'] = $request->ip();

        TestRideRequest::create($data);

        // Support both AJAX (toast) and standard form posts (redirect).
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'ok'      => true,
                'message' => 'Request received. Our team will call you to confirm your test ride.',
            ]);
        }

        return redirect()->route('home')->with(
            'success',
            'Request received. Our team will call you to confirm your test ride.'
        );
    }
}
