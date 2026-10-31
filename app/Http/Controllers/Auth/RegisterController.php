<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $brands = Brand::published()->orderBy('name')->get();

        return view('auth.register', compact('brands'));
    }

    public function register(RegisterRequest $request)
    {
        $data       = $request->validated();
        $newsletter = $request->boolean('newsletter');
        $type       = $data['account_type']; // 'customer' or 'merchant' only

        // Double-check at the controller level — never allow admin self-registration,
        // even if the request were somehow forged past the FormRequest rules.
        if (! in_array($type, ['customer', 'merchant'], true)) {
            abort(403, 'Invalid account type.');
        }

        $user = DB::transaction(function () use ($data, $newsletter, $type) {
            // Merchants start as pending (is_active=false until approved); customers are immediately active.
            $accountStatus = ($type === 'merchant') ? 'pending' : 'active';
            $isActive      = ($type !== 'merchant'); // merchants blocked until admin approves

            $user = User::create([
                'name'                => $data['name'],
                'email'               => $data['email'],
                'phone'               => $data['phone'],
                'country'             => $data['country'] ?? null,
                'city'                => $data['city'] ?? null,
                'password'            => $data['password'],
                'newsletter_opted_in' => $newsletter,
                'is_active'           => $isActive,
                'account_status'      => $accountStatus,
            ]);

            $role = Role::where('slug', $type)->firstOrFail();
            $user->roles()->attach($role->id);

            return $user;
        });

        if ($type === 'merchant') {
            // Do NOT log them in — redirect to a page explaining pending status.
            return redirect()
                ->route('register.pending')
                ->with('pending_name', $user->name);
        }

        // Customer: do NOT log them in either — send them to the login page
        // so they sign in with their new credentials.
        return redirect()
            ->route('login')
            ->with('status', 'Welcome to MOTOVEX! Your account is ready — please log in.');
    }

    /**
     * Show the "your merchant account is pending approval" page.
     * Accessible without authentication (the user was never logged in).
     */
    public function showPendingPage()
    {
        return view('auth.merchant-pending');
    }
}
