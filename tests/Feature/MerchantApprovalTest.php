<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Tests for the complete merchant approval workflow + admin account creation.
 *
 * Scenarios covered:
 *  1.  Customer registration → Active immediately, logged in.
 *  2.  Merchant registration → Pending status, NOT logged in, redirected to pending page.
 *  3.  Admin registration via public form → BLOCKED (403 / validation fail).
 *  4.  Pending merchant CANNOT log in normally.
 *  5.  Pending merchant CANNOT access merchant-protected routes.
 *  6.  Admin sees pending merchant in the pending filter.
 *  7.  Admin approves merchant → status becomes Active.
 *  8.  Approved merchant CAN log in.
 *  9.  Admin rejects merchant → status becomes Rejected.
 * 10.  Rejected merchant CANNOT log in.
 * 11.  Customer CANNOT approve/reject merchants.
 * 12.  Merchant CANNOT access admin panel.
 * 13.  Customer CANNOT reach admin panel.
 * 14.  Admin creates another admin from Users page.
 * 15.  New admin can log in and reach admin dashboard.
 * 16.  Existing admin, customer, approved merchant continue working.
 */
class MerchantApprovalTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ──────────────────────────────────────────────────────────────────

    private function seedRoles(): void
    {
        $roles = [
            ['name' => 'Admin',       'slug' => 'admin'],
            ['name' => 'Data Entry',  'slug' => 'data_entry'],
            ['name' => 'Sales Agent', 'slug' => 'sales_agent'],
            ['name' => 'Customer',    'slug' => 'customer'],
            ['name' => 'Merchant',    'slug' => 'merchant'],
        ];
        foreach ($roles as $r) {
            Role::firstOrCreate(['slug' => $r['slug']], $r);
        }
    }

    private function makeAdmin(): User
    {
        $user = User::create([
            'name'           => 'Admin User',
            'email'          => 'admin@test.com',
            'password'       => Hash::make('Password1'),
            'is_active'      => true,
            'account_status' => 'active',
        ]);
        $user->roles()->attach(Role::where('slug', 'admin')->first());
        return $user;
    }

    private function makeCustomer(?string $email = null): User
    {
        $user = User::create([
            'name'           => 'Customer User',
            'email'          => $email ?? 'customer@test.com',
            'password'       => Hash::make('Password1'),
            'is_active'      => true,
            'account_status' => 'active',
        ]);
        $user->roles()->attach(Role::where('slug', 'customer')->first());
        return $user;
    }

    private function makeMerchant(string $status = 'pending', ?string $email = null): User
    {
        $user = User::create([
            'name'           => 'Merchant User',
            'email'          => $email ?? 'merchant@test.com',
            'password'       => Hash::make('Password1'),
            'is_active'      => $status === 'active',
            'account_status' => $status,
        ]);
        $user->roles()->attach(Role::where('slug', 'merchant')->first());
        return $user;
    }

    private function registrationPayload(string $type, string $email = 'new@test.com'): array
    {
        return [
            'name'                  => 'New User',
            'email'                 => $email,
            'phone'                 => '+971500000099',
            'password'              => 'Password1',
            'password_confirmation' => 'Password1',
            'account_type'          => $type,
            'terms'                 => '1',
        ];
    }

    // ── 1. Customer registration → Active, logged in ─────────────────────────────

    public function test_customer_registration_creates_active_account(): void
    {
        $this->seedRoles();

        $response = $this->post('/register', $this->registrationPayload('customer'));

        $response->assertRedirect(route('account.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'new@test.com')->firstOrFail();
        $this->assertEquals('active', $user->account_status);
        $this->assertTrue($user->is_active);
        $this->assertTrue($user->isCustomer());
    }

    // ── 2. Merchant registration → Pending, NOT logged in, pending page ──────────

    public function test_merchant_registration_creates_pending_account_and_does_not_log_in(): void
    {
        $this->seedRoles();

        $response = $this->post('/register', $this->registrationPayload('merchant'));

        $response->assertRedirect(route('register.pending'));
        $this->assertGuest(); // must NOT be logged in

        $user = User::where('email', 'new@test.com')->firstOrFail();
        $this->assertEquals('pending', $user->account_status);
        $this->assertTrue($user->isMerchant());
    }

    // ── 3. Admin registration via public form → BLOCKED ──────────────────────────

    public function test_public_registration_cannot_create_admin_account(): void
    {
        $this->seedRoles();

        $response = $this->post('/register', $this->registrationPayload('admin'));

        // Must fail validation — 'admin' is not in the allowed list
        $response->assertSessionHasErrors('account_type');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'new@test.com']);
    }

    public function test_public_registration_cannot_create_data_entry_account(): void
    {
        $this->seedRoles();

        $response = $this->post('/register', $this->registrationPayload('data_entry'));

        $response->assertSessionHasErrors('account_type');
        $this->assertGuest();
    }

    // ── 4. Pending merchant CANNOT log in ────────────────────────────────────────

    public function test_pending_merchant_cannot_login(): void
    {
        $this->seedRoles();
        $this->makeMerchant('pending', 'pending@test.com');

        $response = $this->post('/login', [
            'email'    => 'pending@test.com',
            'password' => 'Password1',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ── 5. Pending merchant CANNOT access merchant routes while somehow authed ───

    public function test_pending_merchant_cannot_access_admin_dashboard(): void
    {
        $this->seedRoles();
        $merchant = $this->makeMerchant('pending');

        // Even if somehow authenticated, they should be blocked by the active middleware
        // (is_active = false for pending/rejected) or role check
        $response = $this->actingAs($merchant)->get('/admin');
        // Should be 403 (wrong role) or redirect — not 200
        $this->assertTrue(
            in_array($response->getStatusCode(), [403, 302], true),
            "Expected 403 or redirect, got {$response->getStatusCode()}"
        );
    }

    public function test_pending_merchant_is_blocked_by_active_middleware(): void
    {
        $this->seedRoles();
        // Pending merchant has is_active = false
        $merchant = $this->makeMerchant('pending');
        $this->assertFalse($merchant->is_active);

        $response = $this->actingAs($merchant)->get('/account');
        // The 'active' middleware logs them out and redirects to login
        $response->assertRedirect('/login');
    }

    // ── 6. Admin sees pending merchant in pending filter ─────────────────────────

    public function test_admin_can_see_pending_merchants_in_pending_filter(): void
    {
        $this->seedRoles();
        $admin = $this->makeAdmin();
        $this->makeMerchant('pending', 'pm@test.com');

        $response = $this->actingAs($admin)->get('/admin/users?status=pending');
        $response->assertStatus(200);
        $response->assertSee('pm@test.com');
    }

    // ── 7. Admin approves merchant → Active ──────────────────────────────────────

    public function test_admin_can_approve_pending_merchant(): void
    {
        $this->seedRoles();
        $admin    = $this->makeAdmin();
        $merchant = $this->makeMerchant('pending', 'pm@test.com');

        $response = $this->actingAs($admin)->patch("/admin/users/{$merchant->id}/approve-merchant");

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $merchant->refresh();
        $this->assertEquals('active', $merchant->account_status);
        $this->assertTrue($merchant->is_active);
        $this->assertNotNull($merchant->approved_at);
        $this->assertEquals($admin->id, $merchant->approved_by);
    }

    // ── 8. Approved merchant CAN log in ──────────────────────────────────────────

    public function test_approved_merchant_can_login(): void
    {
        $this->seedRoles();
        $merchant = $this->makeMerchant('active', 'appr@test.com');

        $response = $this->post('/login', [
            'email'    => 'appr@test.com',
            'password' => 'Password1',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($merchant);
    }

    // ── 9. Admin rejects merchant → Rejected ─────────────────────────────────────

    public function test_admin_can_reject_pending_merchant(): void
    {
        $this->seedRoles();
        $admin    = $this->makeAdmin();
        $merchant = $this->makeMerchant('pending', 'pm@test.com');

        $response = $this->actingAs($admin)->patch("/admin/users/{$merchant->id}/reject-merchant");

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $merchant->refresh();
        $this->assertEquals('rejected', $merchant->account_status);
        $this->assertFalse($merchant->is_active);
    }

    // ── 10. Rejected merchant CANNOT log in ──────────────────────────────────────

    public function test_rejected_merchant_cannot_login(): void
    {
        $this->seedRoles();
        $this->makeMerchant('rejected', 'rej@test.com');

        $response = $this->post('/login', [
            'email'    => 'rej@test.com',
            'password' => 'Password1',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ── 11. Customer CANNOT approve/reject merchants ──────────────────────────────

    public function test_customer_cannot_approve_merchant(): void
    {
        $this->seedRoles();
        $customer = $this->makeCustomer();
        $merchant = $this->makeMerchant('pending', 'pm@test.com');

        $response = $this->actingAs($customer)->patch("/admin/users/{$merchant->id}/approve-merchant");

        // Should be redirected (role middleware) or 403
        $this->assertNotEquals(200, $response->getStatusCode());

        $merchant->refresh();
        $this->assertEquals('pending', $merchant->account_status);
    }

    public function test_customer_cannot_reject_merchant(): void
    {
        $this->seedRoles();
        $customer = $this->makeCustomer();
        $merchant = $this->makeMerchant('pending', 'pm@test.com');

        $response = $this->actingAs($customer)->patch("/admin/users/{$merchant->id}/reject-merchant");

        $this->assertNotEquals(200, $response->getStatusCode());

        $merchant->refresh();
        $this->assertEquals('pending', $merchant->account_status);
    }

    // ── 12. Merchant CANNOT access admin panel ────────────────────────────────────

    public function test_approved_merchant_cannot_access_admin_panel(): void
    {
        $this->seedRoles();
        $merchant = $this->makeMerchant('active');

        $response = $this->actingAs($merchant)->get('/admin');

        // Must not get 200 — no admin role
        $this->assertNotEquals(200, $response->getStatusCode());
    }

    // ── 13. Customer CANNOT access admin panel ────────────────────────────────────

    public function test_customer_cannot_access_admin_panel(): void
    {
        $this->seedRoles();
        $customer = $this->makeCustomer();

        $response = $this->actingAs($customer)->get('/admin');

        $this->assertNotEquals(200, $response->getStatusCode());
    }

    // ── 14. Admin creates another admin from Users page ───────────────────────────

    public function test_admin_can_create_another_admin_account(): void
    {
        $this->seedRoles();
        $admin     = $this->makeAdmin();
        $adminRole = Role::where('slug', 'admin')->first();

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'                  => 'New Admin',
            'email'                 => 'newadmin@test.com',
            'password'              => 'Password1',
            'password_confirmation' => 'Password1',
            'role_id'               => $adminRole->id,
            'is_active'             => '1',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHasNoErrors();

        $newAdmin = User::where('email', 'newadmin@test.com')->firstOrFail();
        $this->assertTrue($newAdmin->isAdmin());
        $this->assertTrue($newAdmin->is_active);
        $this->assertEquals('active', $newAdmin->account_status);
    }

    // ── 15. New admin can log in and reach admin dashboard ───────────────────────

    public function test_new_admin_can_login_and_access_dashboard(): void
    {
        $this->seedRoles();
        $existingAdmin = $this->makeAdmin();
        $adminRole     = Role::where('slug', 'admin')->first();

        // Create new admin via admin panel
        $this->actingAs($existingAdmin)->post('/admin/users', [
            'name'                  => 'Second Admin',
            'email'                 => 'admin2@test.com',
            'password'              => 'Password1',
            'password_confirmation' => 'Password1',
            'role_id'               => $adminRole->id,
            'is_active'             => '1',
        ]);

        // Log out and log in as the new admin
        $this->post('/logout');

        $response = $this->post('/login', [
            'email'    => 'admin2@test.com',
            'password' => 'Password1',
        ]);
        $response->assertRedirect(route('admin.dashboard'));

        $newAdmin = User::where('email', 'admin2@test.com')->first();
        $this->assertAuthenticatedAs($newAdmin);
    }

    // ── 16. Existing accounts continue working ───────────────────────────────────

    public function test_existing_admin_continues_to_work(): void
    {
        $this->seedRoles();
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_existing_customer_continues_to_work(): void
    {
        $this->seedRoles();
        $customer = $this->makeCustomer();

        $response = $this->actingAs($customer)->get('/account');
        // /account redirects to account dashboard (302) or renders the page (200) — both are valid
        $this->assertContains($response->getStatusCode(), [200, 302],
            "Expected 200 or 302, got {$response->getStatusCode()}");
    }

    public function test_existing_approved_merchant_account_is_not_blocked(): void
    {
        $this->seedRoles();
        $merchant = $this->makeMerchant('active', 'approved@test.com');

        $this->assertTrue($merchant->is_active);
        $this->assertEquals('active', $merchant->account_status);
        $this->assertTrue($merchant->isApprovedMerchant());
        $this->assertFalse($merchant->isPendingMerchant());
        $this->assertFalse($merchant->isRejectedMerchant());
    }

    // ── Model helper assertions ───────────────────────────────────────────────────

    public function test_user_model_pending_helpers_work_correctly(): void
    {
        $this->seedRoles();

        $pending  = $this->makeMerchant('pending', 'p@test.com');
        $approved = $this->makeMerchant('active',  'a@test.com');
        $rejected = $this->makeMerchant('rejected', 'r@test.com');

        $this->assertTrue($pending->isPendingMerchant());
        $this->assertFalse($pending->isApprovedMerchant());
        $this->assertFalse($pending->isRejectedMerchant());

        $this->assertFalse($approved->isPendingMerchant());
        $this->assertTrue($approved->isApprovedMerchant());
        $this->assertFalse($approved->isRejectedMerchant());

        $this->assertFalse($rejected->isPendingMerchant());
        $this->assertFalse($rejected->isApprovedMerchant());
        $this->assertTrue($rejected->isRejectedMerchant());
    }

    // ── Security: cannot approve twice ───────────────────────────────────────────

    public function test_admin_cannot_approve_already_active_merchant(): void
    {
        $this->seedRoles();
        $admin    = $this->makeAdmin();
        $merchant = $this->makeMerchant('active', 'already@test.com');

        $response = $this->actingAs($admin)->patch("/admin/users/{$merchant->id}/approve-merchant");

        // Should return 422 (not pending)
        $response->assertStatus(422);
    }

    // ── Security: non-merchant cannot be approved via merchant route ──────────────

    public function test_admin_cannot_use_approve_route_on_non_merchant(): void
    {
        $this->seedRoles();
        $admin    = $this->makeAdmin();
        $customer = $this->makeCustomer('cust@test.com');

        $response = $this->actingAs($admin)->patch("/admin/users/{$customer->id}/approve-merchant");

        $response->assertStatus(403);
    }
}
