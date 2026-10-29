<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {
        $role = $request->query('role');

        $users = User::with(['roles', 'brands'])
            ->when($role, fn ($query) => $query->whereHas('roles', fn ($q) => $q->where('slug', $role)))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $roles = Role::orderBy('name')->get();
        $roleCounts = Role::withCount('users')->get()->pluck('users_count', 'slug');

        return view('admin.users.index', compact('users', 'roles', 'role', 'roleCounts'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'brands'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->roles()->attach($data['role_id']);
            $this->syncBrandAssignments($user, $data['brand_ids'] ?? []);

            return $user;
        });

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load('roles', 'brands');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $user->load('roles', 'brands');

        return view('admin.users.edit', compact('user', 'roles', 'brands'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $user) {
            $user->update([
                'name'  => $data['name'],
                'email' => $data['email'],
            ]);

            if (! empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            $adminRole = Role::where('slug', 'admin')->first();
            $isCurrentlyAdmin = $user->isAdmin();
            $willRemainAdmin = $adminRole && (int) $data['role_id'] === (int) $adminRole->id;

            if ($isCurrentlyAdmin && ! $willRemainAdmin) {
                $otherAdmins = User::whereHas('roles', fn ($q) => $q->where('slug', 'admin'))
                    ->where('id', '!=', $user->id)
                    ->count();

                abort_if($otherAdmins === 0, 403, 'Cannot demote the last remaining admin account.');
            }

            // Guard against suspending the last active admin.
            if ($isCurrentlyAdmin && ! ($data['is_active'] ?? true)) {
                $otherActiveAdmins = User::whereHas('roles', fn ($q) => $q->where('slug', 'admin'))
                    ->where('is_active', true)
                    ->where('id', '!=', $user->id)
                    ->count();

                abort_if($otherActiveAdmins === 0, 403, 'Cannot suspend the last remaining active admin account.');
            }

            $user->update(['is_active' => $data['is_active'] ?? true]);

            $user->roles()->sync([$data['role_id']]);
            $this->syncBrandAssignments($user, $data['brand_ids'] ?? []);
        });

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'You cannot delete your own account.');

        if ($user->isAdmin()) {
            $otherAdmins = User::whereHas('roles', fn ($q) => $q->where('slug', 'admin'))
                ->where('id', '!=', $user->id)
                ->count();

            abort_if($otherAdmins === 0, 403, 'Cannot delete the last remaining admin account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleActive(User $user)
    {
        $this->authorize('update', $user);

        abort_if($user->id === auth()->id(), 403, 'You cannot suspend your own account.');

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', sprintf(
            '%s has been %s.',
            $user->name,
            $user->is_active ? 'reactivated' : 'suspended'
        ));
    }

    private function syncBrandAssignments(User $user, array $brandIds): void
    {
        $user->unsetRelation('roles');

        if ($user->isAdmin()) {
            $user->brands()->detach();

            return;
        }

        $syncData = collect($brandIds)
            ->mapWithKeys(fn ($id) => [$id => ['role' => 'data_entry']])
            ->all();

        $user->brands()->sync($syncData);
    }
}
