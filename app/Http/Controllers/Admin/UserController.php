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

    // ──────────────────────────────────────────────────────────────────────────
    // LIST / FILTER
    // ──────────────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $role   = $request->query('role');
        $status = $request->query('status');   // 'pending' | 'rejected' | null

        $users = User::with(['roles', 'brands'])
            ->when($role, fn ($q) => $q->whereHas('roles', fn ($r) => $r->where('slug', $role)))
            ->when($status === 'pending',  fn ($q) => $q->where('account_status', 'pending'))
            ->when($status === 'rejected', fn ($q) => $q->where('account_status', 'rejected'))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $roles     = Role::orderBy('name')->get();
        $roleCounts = Role::withCount('users')->get()->pluck('users_count', 'slug');

        // Count for tab badges
        $pendingCount  = User::pendingMerchants()->count();
        $rejectedCount = User::whereHas('roles', fn ($q) => $q->where('slug', 'merchant'))
                             ->where('account_status', 'rejected')
                             ->count();

        return view('admin.users.index', compact(
            'users', 'roles', 'role', 'status', 'roleCounts', 'pendingCount', 'rejectedCount'
        ));
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CREATE / STORE  (admin-created users including new admins)
    // ──────────────────────────────────────────────────────────────────────────

    public function create()
    {
        $roles  = Role::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        // Pass the admin role id for the "Add New Admin" shortcut.
        $adminRoleId = Role::where('slug', 'admin')->value('id');

        return view('admin.users.create', compact('roles', 'brands', 'adminRoleId'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        // Security: prevent creating an admin via a manipulated role_id unless
        // the current user is already an admin (StoreUserRequest already gates this
        // via UserPolicy::create which requires isAdmin()).
        $selectedRole = Role::findOrFail($data['role_id']);

        // Admin accounts are always active — no pending status.
        $accountStatus = 'active';

        $user = DB::transaction(function () use ($data, $selectedRole, $accountStatus) {
            $user = User::create([
                'name'           => $data['name'],
                'email'          => $data['email'],
                'password'       => Hash::make($data['password']),
                'is_active'      => $data['is_active'] ?? true,
                'account_status' => $accountStatus,
            ]);

            $user->roles()->attach($selectedRole->id);
            $this->syncBrandAssignments($user, $data['brand_ids'] ?? []);

            return $user;
        });

        $roleLabel = $selectedRole->name;

        return redirect()
            ->route('admin.users.index')
            ->with('success', "{$roleLabel} account for {$user->name} created successfully.");
    }

    // ──────────────────────────────────────────────────────────────────────────
    // SHOW / EDIT / UPDATE / DESTROY
    // ──────────────────────────────────────────────────────────────────────────

    public function show(User $user)
    {
        $user->load('roles', 'brands');

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles  = Role::orderBy('name')->get();
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

            $adminRole       = Role::where('slug', 'admin')->first();
            $isCurrentlyAdmin = $user->isAdmin();
            $willRemainAdmin  = $adminRole && (int) $data['role_id'] === (int) $adminRole->id;

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

    // ──────────────────────────────────────────────────────────────────────────
    // TOGGLE ACTIVE
    // ──────────────────────────────────────────────────────────────────────────

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

    // ──────────────────────────────────────────────────────────────────────────
    // MERCHANT APPROVAL / REJECTION
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Approve a pending merchant application.
     */
    public function approveMerchant(User $user)
    {
        $this->authorize('update', $user);

        abort_unless($user->isMerchant(), 403, 'This action is only available for merchant accounts.');
        abort_unless($user->isAccountPending(), 422, 'This account is not in pending status.');

        $user->update([
            'account_status' => 'active',
            'is_active'      => true,
            'approved_at'    => now(),
            'approved_by'    => auth()->id(),
        ]);

        return redirect()
            ->route('admin.users.index', ['status' => 'pending'])
            ->with('success', "Merchant account for {$user->name} has been approved. They can now log in.");
    }

    /**
     * Reject a pending merchant application.
     */
    public function rejectMerchant(User $user)
    {
        $this->authorize('update', $user);

        abort_unless($user->isMerchant(), 403, 'This action is only available for merchant accounts.');
        abort_unless($user->isAccountPending(), 422, 'This account is not in pending status.');

        $user->update([
            'account_status' => 'rejected',
            'is_active'      => false,
        ]);

        return redirect()
            ->route('admin.users.index', ['status' => 'pending'])
            ->with('success', "Merchant application for {$user->name} has been rejected.");
    }

    // ──────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────────────────────

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
