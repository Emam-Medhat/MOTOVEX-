<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Enquiry;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
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

        Enquiry::create($request->validated());

        return redirect()
            ->route('contact')
            ->with('success', 'Thank you! Your enquiry has been received. We will get back to you shortly.');
    }
}
