<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RentalRequestController extends Controller
{
    public function store(Request $request, Motorcycle $motorcycle)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'rental_start_date' => ['required', 'date', 'after_or_equal:today'],
            'rental_end_date' => ['required', 'date', 'after_or_equal:rental_start_date'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $enquiry = DB::transaction(function () use ($request, $motorcycle, $validated) {
            $motorcycle = Motorcycle::lockForUpdate()->findOrFail($motorcycle->id);
            abort_unless(
                $motorcycle->is_published
                    && $motorcycle->availability_status !== 'sold'
                    && $motorcycle->rental_price_per_day > 0,
                404
            );

            $start = now()->parse($validated['rental_start_date'])->startOfDay();
            $end = now()->parse($validated['rental_end_date'])->startOfDay();
            $overlap = Enquiry::where('motorcycle_id', $motorcycle->id)
                ->where('enquiry_type', Enquiry::TYPE_RENTAL)
                ->whereNotIn('status', [Enquiry::STATUS_COMPLETED, Enquiry::STATUS_CANCELLED])
                ->whereDate('rental_start_date', '<=', $end->toDateString())
                ->whereDate('rental_end_date', '>=', $start->toDateString())
                ->exists();

            if ($overlap) {
                throw ValidationException::withMessages([
                    'rental_start_date' => 'This motorcycle already has a request for overlapping rental dates.',
                ]);
            }

            $days = $start->diffInDays($end) + 1;
            $dailyRate = (float) $motorcycle->rental_price_per_day;
            $name = $motorcycle->brand->name . ' ' . $motorcycle->model_name;

            return Enquiry::create([
                'user_id' => $request->user()?->id,
                'order_reference' => Enquiry::generateOrderReference(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'subject' => 'Rental request: ' . $name,
                'message' => $validated['message'] ?? '',
                'enquiry_type' => Enquiry::TYPE_RENTAL,
                'status' => Enquiry::STATUS_PENDING,
                'motorcycle_id' => $motorcycle->id,
                'motorcycle_snapshot' => $name,
                'rental_start_date' => $start->toDateString(),
                'rental_end_date' => $end->toDateString(),
                'rental_days' => $days,
                'rental_price_per_day' => $dailyRate,
                'unit_price' => $dailyRate,
                'total_amount' => $dailyRate * $days,
                'currency' => $motorcycle->currency ?? 'USD',
                'payment_status' => Enquiry::PAY_PENDING,
            ]);
        });

        return redirect()->route('motorcycles.show', $motorcycle->slug)
            ->with('rental_success', $enquiry->order_reference);
    }
}
