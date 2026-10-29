<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        $data = $request->validated();
        $newsletter = $request->boolean('newsletter');

        $user = DB::transaction(function () use ($data, $newsletter) {
            $user = User::create([
                'name'               => $data['name'],
                'email'              => $data['email'],
                'phone'              => $data['phone'],
                'country'            => $data['country'] ?? null,
                'city'               => $data['city'] ?? null,
                'password'           => $data['password'],
                'newsletter_opted_in' => $newsletter,
                'is_active'          => true,
            ]);

            $role = Role::where('slug', $data['account_type'])->firstOrFail();
            $user->roles()->attach($role->id);

            // If registering as a Dealer (data_entry), assign all current brands so they can start working.
            if ($data['account_type'] === 'data_entry') {
                $brandIds = \App\Models\Brand::pluck('id')->toArray();
                $syncData = collect($brandIds)
                    ->mapWithKeys(fn ($id) => [$id => ['role' => 'data_entry']])
                    ->all();
                $user->brands()->attach($syncData);
            }

            return $user;
        });

        return redirect()
            ->route('login')
            ->with('success', 'Registration successful! Please sign in with your new account.');
    }
}
