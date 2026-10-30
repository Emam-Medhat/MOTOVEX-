<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Motorcycle;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'type' => ['nullable', 'in:purchase,rental,product,general,test_drive,service,parts,test_ride'],
            'status' => ['nullable', 'string', 'max:30'],
            'manager_id' => ['nullable', 'integer', 'exists:users,id'],
            'motorcycle_id' => ['nullable', 'integer', 'exists:motorcycles,id'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);
        $user = $request->user();
        $enquiries = $this->enquiryRecords($user, $filters);
        $testRides = $this->testRideRecords($user, $filters);

        $records = DB::query()->fromSub($enquiries->unionAll($testRides), 'requests')
            ->orderByDesc('submitted_at')
            ->paginate(20)
            ->withQueryString();

        $managers = User::whereHas('roles', fn ($query) => $query->whereIn('slug', ['data_entry', 'sales_agent']))
            ->orderBy('name')
            ->get();
        $motorcycles = Motorcycle::orderBy('model_name')->get();

        return view('admin.requests.index', compact('records', 'managers', 'motorcycles'));
    }

    private function enquiryRecords(User $user, array $filters): Builder
    {
        $query = DB::table('enquiries as e')
            ->leftJoin('motorcycles as m', 'm.id', '=', 'e.motorcycle_id')
            ->selectRaw("
                e.id as record_id, e.enquiry_type as request_type, e.order_reference as reference,
                e.name as customer_name, e.email as customer_email, e.phone as customer_phone,
                COALESCE(e.motorcycle_snapshot, m.model_name) as motorcycle_name,
                m.image_url as image_url, m.image_path as image_path, e.motorcycle_id as motorcycle_id,
                e.status as status, e.created_at as submitted_at, e.payment_status as payment_status,
                e.total_amount as total_amount, e.currency as currency,
                e.rental_start_date as rental_start_date, e.rental_end_date as rental_end_date,
                e.rental_days as rental_days, e.rental_price_per_day as rental_price_per_day,
                e.preferred_contact_time as requested_time
            ");

        if (! $user->isAdmin() && ! $user->isSalesAgent()) {
            $query->whereExists(fn ($subquery) => $subquery->selectRaw('1')
                ->from('motorcycles as owned')
                ->whereColumn('owned.id', 'e.motorcycle_id')
                ->where(fn ($scope) => $scope->where('owned.assigned_manager_id', $user->id)
                    ->orWhere(fn ($fallback) => $fallback->whereNull('owned.assigned_manager_id')
                        ->where('owned.created_by', $user->id))));
        }

        $this->applyFilters($query, $filters, $user, 'e.motorcycle_id', 'e.enquiry_type', 'e.created_at', 'e.name', 'e.email', 'e.order_reference', 'e.motorcycle_snapshot', 'm.model_name');

        return $query;
    }

    private function testRideRecords(User $user, array $filters): Builder
    {
        $query = DB::table('test_ride_requests as t')
            ->leftJoin('motorcycles as m', 'm.id', '=', 't.motorcycle_id')
            ->selectRaw("
                t.id as record_id, 'test_ride' as request_type, t.booking_reference as reference,
                t.name as customer_name, t.email as customer_email, t.phone as customer_phone,
                m.model_name as motorcycle_name, m.image_url as image_url, m.image_path as image_path,
                t.motorcycle_id as motorcycle_id, t.status as status, t.created_at as submitted_at,
                NULL as payment_status, NULL as total_amount, NULL as currency,
                NULL as rental_start_date, NULL as rental_end_date, NULL as rental_days,
                NULL as rental_price_per_day, t.preferred_time as requested_time
            ");

        if (! $user->isAdmin() && ! $user->isSalesAgent()) {
            $query->whereExists(fn ($subquery) => $subquery->selectRaw('1')
                ->from('motorcycles as owned')
                ->whereColumn('owned.id', 't.motorcycle_id')
                ->where(fn ($scope) => $scope->where('owned.assigned_manager_id', $user->id)
                    ->orWhere(fn ($fallback) => $fallback->whereNull('owned.assigned_manager_id')
                        ->where('owned.created_by', $user->id))));
        }

        $this->applyFilters($query, $filters, $user, 't.motorcycle_id', "'test_ride'", 't.created_at', 't.name', 't.email', 't.booking_reference', 'm.model_name');
        if (! empty($filters['date_from'])) {
            $query->whereDate('t.preferred_date', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('t.preferred_date', '<=', $filters['date_to']);
        }

        return $query;
    }

    private function applyFilters(
        Builder $query,
        array $filters,
        User $user,
        string $motorcycleColumn,
        string $typeColumn,
        string $dateColumn,
        string $nameColumn,
        string $emailColumn,
        string $referenceColumn,
        string $snapshotColumn,
        string $modelColumn
    ): void {
        $query->when($filters['type'] ?? null, fn ($q, $type) => $q->whereRaw($typeColumn . ' = ?', [$type]));
        $query->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status));
        $query->when($filters['motorcycle_id'] ?? null, fn ($q, $id) => $q->where($motorcycleColumn, $id));
        $query->when($filters['date_from'] ?? null, fn ($q, $date) => $q->whereDate($dateColumn, '>=', $date));
        $query->when($filters['date_to'] ?? null, fn ($q, $date) => $q->whereDate($dateColumn, '<=', $date));

        if ($user->isAdmin() && ! empty($filters['manager_id'])) {
            $managerId = $filters['manager_id'];
            $query->whereExists(fn ($subquery) => $subquery->selectRaw('1')
                ->from('motorcycles as assigned')
                ->whereColumn('assigned.id', $motorcycleColumn)
                ->where(fn ($scope) => $scope->where('assigned.assigned_manager_id', $managerId)
                    ->orWhere(fn ($fallback) => $fallback->whereNull('assigned.assigned_manager_id')
                        ->where('assigned.created_by', $managerId))));
        }

        if (! empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            $query->where(fn ($q) => $q->where($nameColumn, 'like', $search)
                ->orWhere($emailColumn, 'like', $search)
                ->orWhere($referenceColumn, 'like', $search)
                ->orWhere($snapshotColumn, 'like', $search)
                ->orWhere($modelColumn, 'like', $search));
        }
    }
}
