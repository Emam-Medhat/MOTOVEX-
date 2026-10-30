<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Motorcycle;
use App\Models\TestRideRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    /** Enquiry types displayed outside purchase and rental request lists. */
    private const TYPES = ['product', 'test_drive', 'service', 'parts', 'general'];

    public function index(Request $request)
    {
        $this->authorize('viewAny', Enquiry::class);

        $search = $request->filled('search') ? trim($request->input('search')) : null;
        $type   = $request->filled('enquiry_type') ? $request->input('enquiry_type') : null;
        $status = $request->filled('status') ? $request->input('status') : null;

        // Summary cards reflect the searched/typed scope but ignore the status
        // filter — the cards themselves are the status breakdown.
        $scope = Enquiry::query()->whereNotIn('enquiry_type', [Enquiry::TYPE_PURCHASE, Enquiry::TYPE_RENTAL]);
        $this->scopeForActor($scope);
        $this->applyFilters($scope, $search, $type, null);
        $this->applyAdminFilters($scope, $request);

        $statusCounts = (clone $scope)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $stats = [
            'total'     => (int) $statusCounts->sum(),
            'pending'   => (int) ($statusCounts['pending'] ?? 0),
            'contacted' => (int) ($statusCounts['contacted'] ?? 0),
            'completed' => (int) ($statusCounts['completed'] ?? 0),
        ];

        $query = Enquiry::query()->whereNotIn('enquiry_type', [Enquiry::TYPE_PURCHASE, Enquiry::TYPE_RENTAL]);
        $this->scopeForActor($query);
        $this->applyFilters($query, $search, $type, $status);
        $this->applyAdminFilters($query, $request);

        $enquiries = $query->latest()->paginate(15)->withQueryString();

        $enquiryTypes = self::TYPES;
        $managers = User::whereHas('roles', fn ($query) => $query->whereIn('slug', ['data_entry', 'sales_agent']))
            ->orderBy('name')->get();
        $motorcycles = Motorcycle::orderBy('model_name')->get();

        return view('admin.enquiries.index', compact('enquiries', 'stats', 'enquiryTypes', 'managers', 'motorcycles'));
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', Enquiry::class);

        $search = $request->filled('search') ? trim($request->input('search')) : null;
        $type   = $request->filled('enquiry_type') ? $request->input('enquiry_type') : null;
        $status = $request->filled('status') ? $request->input('status') : null;

        $query = Enquiry::query()->whereNotIn('enquiry_type', [Enquiry::TYPE_PURCHASE, Enquiry::TYPE_RENTAL]);
        $this->scopeForActor($query);
        $this->applyFilters($query, $search, $type, $status);
        $this->applyAdminFilters($query, $request);

        $filename = 'enquiries-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
            fputcsv($out, ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Type', 'Status', 'Message', 'Received']);

            $query->latest()->chunk(200, function ($enquiries) use ($out) {
                foreach ($enquiries as $enquiry) {
                    fputcsv($out, [
                        $enquiry->id,
                        $enquiry->name,
                        $enquiry->email,
                        $enquiry->phone,
                        $enquiry->subject,
                        str_replace('_', ' ', $enquiry->enquiry_type),
                        $enquiry->status,
                        $enquiry->message,
                        optional($enquiry->created_at)->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function applyFilters(Builder $query, ?string $search, ?string $type, ?string $status): void
    {
        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('enquiry_type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }
    }

    private function scopeForActor(Builder $query): void
    {
        $user = auth()->user();
        if ($user->isAdmin() || $user->isSalesAgent()) {
            return;
        }

        $query->whereHas('motorcycle', fn ($motorcycles) => $motorcycles->responsibleFor($user));
    }

    private function applyAdminFilters(Builder $query, Request $request): void
    {
        if (! auth()->user()->isAdmin()) {
            return;
        }

        $filters = $request->validate([
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $query->when($filters['manager_id'] ?? null, fn ($q, $id) => $q->whereHas(
            'motorcycle',
            fn ($motorcycles) => $motorcycles->where(function ($scope) use ($id) {
                $scope->where('assigned_manager_id', $id)
                    ->orWhere(function ($fallback) use ($id) {
                        $fallback->whereNull('assigned_manager_id')->where('created_by', $id);
                    });
            })
        ));
        $query->when($filters['motorcycle_id'] ?? null, fn ($q, $id) => $q->where('motorcycle_id', $id));
        $query->when($filters['date_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date));
        $query->when($filters['date_to'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
    }

    public function show(Enquiry $enquiry)
    {
        $this->authorize('view', $enquiry);

        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $this->authorize('update', $enquiry);

        $data = $request->validate([
            'status' => ['required', 'in:pending,contacted,completed,cancelled'],
        ]);

        if ($data['status'] !== $enquiry->status && ! auth()->user()->isStaff() && ! Enquiry::canTransitionStatus($enquiry->status, $data['status'])) {
            abort(422, 'This enquiry status transition is not allowed.');
        }
        $enquiry->update($data);

        return redirect()->route('admin.enquiries.show', $enquiry)->with('success', 'Enquiry status updated.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $this->authorize('delete', $enquiry);

        $enquiry->update(['status' => Enquiry::STATUS_CANCELLED]);

        return redirect()->route('admin.enquiries.index')->with('success', 'Enquiry cancelled and retained in the audit history.');
    }

    // ════════════════════════════════════════════════════════
    // PURCHASE ORDERS — separate view filtering enquiry_type=purchase
    // ════════════════════════════════════════════════════════

    public function ordersIndex(Request $request)
    {
        $type = $request->input('type', Enquiry::TYPE_PURCHASE);
        abort_unless(in_array($type, [Enquiry::TYPE_PURCHASE, Enquiry::TYPE_RENTAL], true), 404);

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'status' => ['nullable', 'in:pending,contacted,completed,cancelled'],
            'payment' => ['nullable', 'in:pending,verified,failed,refunded'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);
        $search = isset($filters['search']) ? trim($filters['search']) : null;
        $status = $filters['status'] ?? null;
        $payment = $filters['payment'] ?? null;

        $base = Enquiry::where('enquiry_type', $type);
        $this->scopeForActor($base);
        $this->applyAdminFilters($base, $request);
        $stats = [
            'total'    => (clone $base)->count(),
            'pending'  => (clone $base)->where('status', 'pending')->count(),
            'confirmed'=> (clone $base)->where('status', 'contacted')->count(),
            'completed'=> (clone $base)->where('status', 'completed')->count(),
            'pay_pending'  => (clone $base)->where('payment_status', 'pending')->count(),
            'pay_verified' => (clone $base)->where('payment_status', 'verified')->count(),
        ];

        $query = Enquiry::where('enquiry_type', $type)
            ->with(['motorcycle.brand', 'user'])
            ->latest();
        $this->scopeForActor($query);
        $this->applyAdminFilters($query, $request);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('order_reference', 'like', "%{$search}%")
                  ->orWhere('motorcycle_snapshot', 'like', "%{$search}%");
            });
        }
        if ($status)  { $query->where('status', $status); }
        if ($payment) { $query->where('payment_status', $payment); }

        $orders = $query->paginate(20)->withQueryString();

        $managers = User::whereHas('roles', fn ($query) => $query->whereIn('slug', ['data_entry', 'sales_agent']))
            ->orderBy('name')->get();
        $motorcycles = Motorcycle::orderBy('model_name')->get();

        return view('admin.orders.index', compact('orders', 'stats', 'type', 'managers', 'motorcycles'));
    }

    public function rentalsIndex(Request $request)
    {
        $request->merge(['type' => Enquiry::TYPE_RENTAL]);

        return $this->ordersIndex($request);
    }

    public function orderShow(Enquiry $enquiry)
    {
        $this->authorize('view', $enquiry);
        abort_unless(in_array($enquiry->enquiry_type, [Enquiry::TYPE_PURCHASE, Enquiry::TYPE_RENTAL], true), 404);

        $enquiry->load(['motorcycle.brand', 'motorcycle.assignedManager', 'user', 'paymentVerifiedBy']);

        return view('admin.orders.show', compact('enquiry'));
    }

    public function orderUpdateStatus(Request $request, Enquiry $enquiry)
    {
        $this->authorize('update', $enquiry);
        abort_unless(in_array($enquiry->enquiry_type, [Enquiry::TYPE_PURCHASE, Enquiry::TYPE_RENTAL], true), 404);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,contacted,completed,cancelled'],
        ]);

        if ($validated['status'] !== $enquiry->status
            && ! Enquiry::canTransitionStatus($enquiry->status, $validated['status'])
            && ! $request->user()->isStaff()) {
            abort(422, 'This request status transition is not allowed.');
        }
        $enquiry->update(['status' => $validated['status']]);

        $route = $enquiry->enquiry_type === Enquiry::TYPE_RENTAL ? 'admin.rentals.show' : 'admin.orders.show';
        return redirect()->route($route, $enquiry)
            ->with('success', 'Order #' . $enquiry->order_reference . ' status updated to ' . $validated['status'] . '.');
    }

    public function orderVerifyPayment(Request $request, Enquiry $enquiry)
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Only administrators can verify payments.');
        abort_unless($enquiry->enquiry_type === Enquiry::TYPE_PURCHASE, 404);

        $validated = $request->validate([
            'payment_status'    => ['required', 'in:pending,verified,failed,refunded'],
            'payment_reference' => ['required_if:payment_status,verified', 'nullable', 'string', 'max:100'],
            'verification_confirmed' => ['required_if:payment_status,verified', 'accepted'],
            'admin_notes'       => ['nullable', 'string', 'max:2000'],
        ]);

        $updates = [
            'payment_status'    => $validated['payment_status'],
            'payment_reference' => $validated['payment_reference'] ?? null,
            'admin_notes'       => $validated['admin_notes'] ?? $enquiry->admin_notes,
        ];

        if ($validated['payment_status'] === 'verified') {
            $updates['payment_verified_at'] = now();
            $updates['payment_verified_by'] = auth()->id();
        } else {
            $updates['payment_verified_at'] = null;
            $updates['payment_verified_by'] = null;
        }

        $enquiry->update($updates);

        return redirect()->route('admin.orders.show', $enquiry)
            ->with('success', 'Payment status updated to ' . $validated['payment_status'] . '.');
    }

    public function orderDestroy(Enquiry $enquiry)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        abort_unless(in_array($enquiry->enquiry_type, [Enquiry::TYPE_PURCHASE, Enquiry::TYPE_RENTAL], true), 404);

        $ref = $enquiry->order_reference;
        $enquiry->update(['status' => Enquiry::STATUS_CANCELLED]);

        $index = $enquiry->enquiry_type === Enquiry::TYPE_RENTAL ? 'admin.rentals.index' : 'admin.orders.index';
        return redirect()->route($index)
            ->with('success', 'Request #' . $ref . ' cancelled and retained in the audit history.');
    }
}
