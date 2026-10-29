<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestRideRequest;
use App\Models\Motorcycle;
use App\Models\Showroom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TestRideController extends Controller
{
    // ── Index (list + filter + search) ───────────────────────────────────────
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $query = TestRideRequest::with(['motorcycle.brand', 'showroom', 'user'])
            ->latest();

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name',              'like', "%{$s}%")
                  ->orWhere('email',            'like', "%{$s}%")
                  ->orWhere('phone',            'like', "%{$s}%")
                  ->orWhere('booking_reference','like', "%{$s}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('preferred_date', $request->date);
        }

        $bookings = $query->paginate(20)->withQueryString();

        $stats = [
            'total'     => TestRideRequest::count(),
            'pending'   => TestRideRequest::where('status', 'pending')->count(),
            'confirmed' => TestRideRequest::where('status', 'confirmed')->count(),
            'completed' => TestRideRequest::where('status', 'completed')->count(),
            'cancelled' => TestRideRequest::where('status', 'cancelled')->count(),
        ];

        return view('admin.test-rides.index', compact('bookings', 'stats'));
    }

    // ── Show detail ──────────────────────────────────────────────────────────
    public function show(TestRideRequest $testRide)
    {
        $this->authorizeAdmin();

        $testRide->load(['motorcycle.brand', 'showroom', 'user']);

        return view('admin.test-rides.show', compact('testRide'));
    }

    // ── Update status / admin notes ──────────────────────────────────────────
    public function update(Request $request, TestRideRequest $testRide)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status'      => ['required', Rule::in(array_keys(TestRideRequest::$statuses))],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $testRide->update($validated);

        return redirect()
            ->route('admin.test-rides.show', $testRide)
            ->with('success', 'Booking #' . $testRide->booking_reference . ' updated to ' . $validated['status'] . '.');
    }

    // ── Quick status update (from index table) ───────────────────────────────
    public function updateStatus(Request $request, TestRideRequest $testRide)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(TestRideRequest::$statuses))],
        ]);

        $testRide->update(['status' => $validated['status']]);

        return redirect()
            ->route('admin.test-rides.index')
            ->with('success', 'Booking #' . $testRide->booking_reference . ' marked as ' . $validated['status'] . '.');
    }

    // ── Destroy ──────────────────────────────────────────────────────────────
    public function destroy(TestRideRequest $testRide)
    {
        $this->authorizeStrictAdmin();

        $ref = $testRide->booking_reference;
        $testRide->delete();

        return redirect()
            ->route('admin.test-rides.index')
            ->with('success', 'Booking #' . $ref . ' has been deleted.');
    }

    // ── Permission helpers ───────────────────────────────────────────────────
    private function authorizeAdmin(): void
    {
        $user = auth()->user();
        abort_unless($user->isAdmin() || $user->isSalesAgent(), 403, 'Unauthorized.');
    }

    private function authorizeStrictAdmin(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Only administrators can delete bookings.');
    }
}
