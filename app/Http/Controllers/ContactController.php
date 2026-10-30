<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Enquiry;
use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function show(Request $request)
    {
        $motorcycle = null;
        if ($request->filled('motorcycle_id')) {
            $motorcycle = Motorcycle::published()->findOrFail($request->integer('motorcycle_id'));
        }

        return view('contact', compact('motorcycle'));
    }

    public function store(StoreContactRequest $request)
    {
        $key = 'contact|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Too many submissions. Please try again in {$seconds} seconds.",
            ]);
        }

        RateLimiter::hit($key, 60);

        $data = $request->validated();
        $data['user_id'] = $request->user()?->id;
        if (! empty($data['motorcycle_id'])) {
            $motorcycle = Motorcycle::findOrFail($data['motorcycle_id']);
            $data['enquiry_type'] = Enquiry::TYPE_PRODUCT;
            $data['subject'] = 'Product enquiry: ' . $motorcycle->model_name;
        }
        Enquiry::create($data);

        return redirect()
            ->route('contact')
            ->with('success', 'Thank you! Your enquiry has been received. We will get back to you shortly.');
    }
}
