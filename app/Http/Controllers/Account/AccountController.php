<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        $enquiries = Enquiry::where('email', $user->email)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total'   => Enquiry::where('email', $user->email)->count(),
            'open'    => Enquiry::where('email', $user->email)->where('status', 'pending')->count(),
            'test_rides' => Enquiry::where('email', $user->email)->where('enquiry_type', 'test_drive')->count(),
        ];

        return view('account.dashboard', compact('user', 'enquiries', 'stats'));
    }

    public function enquiries()
    {
        $enquiries = Enquiry::where('email', auth()->user()->email)
            ->latest()
            ->paginate(10);

        return view('account.enquiries', compact('enquiries'));
    }

    public function editProfile()
    {
        $brands = Brand::published()->orderBy('name')->get();

        return view('account.profile', compact('brands'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'               => ['required', 'string', 'max:100'],
            'phone'              => ['nullable', 'string', 'max:30'],
            'country'            => ['nullable', 'string', 'max:100'],
            'city'               => ['nullable', 'string', 'max:100'],
            'preferred_brand_id' => ['nullable', 'exists:brands,id'],
            'newsletter'         => ['nullable', 'boolean'],
        ]);

        $user->update([
            'name'                => $data['name'],
            'phone'               => $data['phone'] ?? null,
            'country'             => $data['country'] ?? null,
            'city'                => $data['city'] ?? null,
            'preferred_brand_id'  => $data['preferred_brand_id'] ?? null,
            'newsletter_opted_in' => $request->boolean('newsletter'),
        ]);

        return redirect()->route('account.profile')->with('success', 'Your profile has been updated.');
    }

    public function editSecurity()
    {
        return view('account.security');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('account.security')->with('success', 'Your password has been changed.');
    }
}
