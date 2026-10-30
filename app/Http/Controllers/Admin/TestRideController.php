<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestRideRequest;
use App\Models\Motorcycle;
use App\Models\Showroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TestRideController extends Controller
{
    // ── Index (list + filter + search) ───────────────────────────────────────
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'status' => ['nullable', 'in:pending,confirmed,completed,cancelled'],
            'date' => ['nullable', 'date'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
        ]);
        $query = TestRideRequest::with(['motorcycle.brand', 'showroom', 'user'])
            ->latest();
        $this->scopeForActor($query);
        $this->applyAdminFilters($query, $request, $filters);

        // Search
        if (! empty($filters['search'])) {
            $s = $filters['search'];
            $query->where(function ($q) use ($s) {
                $q->where('name',              'like', "%{$s}%")
                  ->orWhere('email',            'like', "%{$s}%")
                  ->orWhere('phone',            'like', "%{$s}%")
                  ->orWhere('booking_reference','like', "%{$s}%");
            });
        }

        // Status filter
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Date filter
        if (! empty($filters['date'])) {
            $query->whereDate('preferred_date', $filters['date']);
        }

        $bookings = $query->paginate(20)->withQueryString();

        $base = TestRideRequest::query();
        $this->scopeForActor($base);
        $this->applyAdminFilters($base, $request, $filters);
        $stats = [];
        foreach (array_keys(TestRideRequest::$statuses) as $status) {
            $stats[$status] = (clone $base)->where('status', $status)->count();
        }
        $stats['total'] = (clone $base)->count();
        $managers = User::whereHas('roles', fn ($query) => $query->whereIn('slug', ['data_entry', 'sales_agent']))
            ->orderBy('name')->get();
        $motorcycles = \App\Models\Motorcycle::orderBy('model_name')->get();

        return view('admin.test-rides.index', compact('bookings', 'stats', 'managers', 'motorcycles'));
    }

    // ── Show detail ──────────────────────────────────────────────────────────
    public function show(TestRideRequest $testRide)
    {
        $this->authorizeRecord($testRide);

        $testRide->load(['motorcycle.brand', 'showroom', 'user']);

        return view('admin.test-rides.show', compact('testRide'));
    }

    // ── Update status / admin notes ──────────────────────────────────────────
    public function update(Request $request, TestRideRequest $testRide)
    {
        $this->authorizeRecord($testRide);

        $validated = $request->validate([
            'status'      => ['required', Rule::in(array_keys(TestRideRequest::$statuses))],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['status'] !== $testRide->status
            && ! TestRideRequest::canTransitionStatus($testRide->status, $validated['status'])) {
            abort(422, 'This booking status transition is not allowed.');
        }
        $testRide->update($validated);

        return redirect()
            ->route('admin.test-rides.show', $testRide)
            ->with('success', 'Booking #' . $testRide->booking_reference . ' updated to ' . $validated['status'] . '.');
    }

    // ── Quick status update (from index table) ───────────────────────────────
    public function updateStatus(Request $request, TestRideRequest $testRide)
    {
        $this->authorizeRecord($testRide);

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(TestRideRequest::$statuses))],
        ]);

        if ($validated['status'] !== $testRide->status
            && ! TestRideRequest::canTransitionStatus($testRide->status, $validated['status'])) {
            abort(422, 'This booking status transition is not allowed.');
        }
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
        $testRide->update(['status' => TestRideRequest::STATUS_CANCELLED]);

        return redirect()
            ->route('admin.test-rides.index')
            ->with('success', 'Booking #' . $ref . ' cancelled and retained in the audit history.');
    }

    // ── Permission helpers ───────────────────────────────────────────────────
    private function scopeForActor($query): void
    {
        $user = auth()->user();
        if (! $user->isAdmin() && ! $user->isSalesAgent()) {
            $query->whereHas('motorcycle', fn ($motorcycles) => $motorcycles->responsibleFor($user));
        }
    }

    private function applyAdminFilters($query, Request $request, array $filters): void
    {
        if (! auth()->user()->isAdmin()) {
            return;
        }

        if (! empty($filters['manager_id'])) {
            $managerId = $filters['manager_id'];
            $query->whereHas('motorcycle', fn ($motorcycles) => $motorcycles->where(function ($scope) use ($managerId) {
                $scope->where('assigned_manager_id', $managerId)
                    ->orWhere(fn ($fallback) => $fallback->whereNull('assigned_manager_id')->where('created_by', $managerId));
            }));
        }
        if (! empty($filters['motorcycle_id'])) {
            $query->where('motorcycle_id', $filters['motorcycle_id']);
        }
    }

    private function authorizeAdmin(): void
    {
        $user = auth()->user();
        abort_unless(
            $user && ($user->isAdmin() || $user->isSalesAgent() || $user->isDataEntry()),
            403,
            'Unauthorized.'
        );
    }

    private function authorizeRecord(TestRideRequest $testRide): void
    {
        $user = auth()->user();
        abort_unless(
            $user->isAdmin()
                || $user->isSalesAgent()
                || ($user->isDataEntry() && $testRide->motorcycle?->responsibleManagerId() === $user->id),
            403,
            'Unauthorized.'
        );
    }

    private function authorizeStrictAdmin(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Only administrators can cancel bookings.');
    }
}
