<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Enquiry;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['admin', 'data_entry', 'sales_agent', 'customer'] as $slug) {
            Role::create(['name' => str($slug)->headline(), 'slug' => $slug]);
        }
    }

    private function userWithRole(string $roleSlug, array $attributes = []): User
    {
        $user = User::create(array_merge([
            'name'     => 'Test ' . $roleSlug,
            'email'    => $roleSlug . '-' . uniqid() . '@test.com',
            'password' => Hash::make('password123'),
        ], $attributes));

        $user->roles()->attach(Role::where('slug', $roleSlug)->first());

        return $user;
    }

    private function brand(): Brand
    {
        return Brand::create([
            'name'         => 'Test Brand ' . uniqid(),
            'slug'         => 'test-brand-' . uniqid(),
            'description'  => 'Brand',
            'is_published' => true,
        ]);
    }

    // ── Registration ────────────────────────────────────────────────────────────

    public function test_guest_can_view_registration_form(): void
    {
        $this->get('/register')->assertStatus(200);
    }

    public function test_customer_can_register_and_is_logged_in(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'New Rider',
            'email'                 => 'new-rider@example.com',
            'phone'                 => '+971500000001',
            'country'               => 'United Arab Emirates',
            'city'                  => 'Dubai',
            'account_type'          => 'customer',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
            'terms'                 => '1',
            'newsletter'            => '1',
        ]);

        $response->assertRedirect(route('account.dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'new-rider@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isCustomer());
        $this->assertTrue($user->is_active);
        $this->assertTrue($user->newsletter_opted_in);
    }

    public function test_dealer_can_register_as_merchant_and_gets_pending_status(): void
    {
        $this->brand(); // Create at least one brand

        $response = $this->post('/register', [
            'name'                  => 'New Dealer',
            'email'                 => 'dealer@example.com',
            'phone'                 => '+971500000005',
            'account_type'          => 'merchant',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
            'terms'                 => '1',
        ]);

        // Merchant registration redirects to pending page — NOT logged in
        $response->assertRedirect(route('register.pending'));
        $this->assertGuest();

        $user = User::where('email', 'dealer@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->isMerchant());
        $this->assertEquals('pending', $user->account_status);
        $this->assertFalse($user->is_active);
    }

    public function test_admin_cannot_register_via_public_form(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'New Admin',
            'email'                 => 'newadmin@example.com',
            'phone'                 => '+971500000006',
            'account_type'          => 'admin',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
            'terms'                 => '1',
        ]);

        // Must be blocked — 'admin' is not allowed in public registration
        $response->assertSessionHasErrors('account_type');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'newadmin@example.com']);
    }

    public function test_registration_rejects_duplicate_email(): void
    {
        $this->userWithRole('customer', ['email' => 'taken@example.com']);

        $response = $this->post('/register', [
            'name'                  => 'Another Rider',
            'email'                 => 'taken@example.com',
            'phone'                 => '+971500000002',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
            'terms'                 => '1',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registration_requires_terms_and_a_strong_password(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'No Terms',
            'email'                 => 'no-terms@example.com',
            'phone'                 => '+971500000003',
            'password'              => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['terms', 'password']);
        $this->assertDatabaseMissing('users', ['email' => 'no-terms@example.com']);
    }

    public function test_registration_rejects_requests_that_fill_the_honeypot(): void
    {
        $response = $this->post('/register', [
            'name'                  => 'Spam Bot',
            'email'                 => 'bot@example.com',
            'phone'                 => '+971500000004',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
            'terms'                 => '1',
            'website'               => 'http://spam.example.com',
        ]);

        $response->assertSessionHasErrors('website');
        $this->assertDatabaseMissing('users', ['email' => 'bot@example.com']);
    }

    // ── Login routing ───────────────────────────────────────────────────────────

    public function test_staff_login_redirects_to_the_admin_dashboard(): void
    {
        $admin = $this->userWithRole('admin', ['email' => 'admin-login@test.com']);

        $this->post('/login', ['email' => 'admin-login@test.com', 'password' => 'password123'])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_customer_login_redirects_to_their_account(): void
    {
        $customer = $this->userWithRole('customer', ['email' => 'customer-login@test.com']);

        $this->post('/login', ['email' => 'customer-login@test.com', 'password' => 'password123'])
            ->assertRedirect(route('account.dashboard'));

        $this->assertAuthenticatedAs($customer);
    }

    public function test_suspended_account_cannot_sign_in(): void
    {
        $this->userWithRole('customer', ['email' => 'suspended@test.com', 'is_active' => false]);

        $this->post('/login', ['email' => 'suspended@test.com', 'password' => 'password123'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    // ── Middleware / permissions ────────────────────────────────────────────────

    public function test_suspended_user_session_is_invalidated_by_active_middleware(): void
    {
        $customer = $this->userWithRole('customer');
        $customer->update(['is_active' => false]);

        $this->actingAs($customer)
            ->get('/account')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_customer_cannot_access_the_admin_area(): void
    {
        $customer = $this->userWithRole('customer');

        $this->actingAs($customer)->get('/admin')->assertStatus(403);
        $this->actingAs($customer)->get('/admin/users')->assertStatus(403);
    }

    public function test_sales_agent_can_view_enquiries_but_not_users(): void
    {
        $agent = $this->userWithRole('sales_agent');

        $this->actingAs($agent)->get('/admin')->assertStatus(200);
        $this->actingAs($agent)->get('/admin/enquiries')->assertStatus(200);
        $this->actingAs($agent)->get('/admin/users')->assertStatus(403);
        $this->actingAs($agent)->get('/admin/brands')->assertStatus(403);
    }

    public function test_sales_agent_cannot_delete_an_enquiry(): void
    {
        $agent = $this->userWithRole('sales_agent');
        $enquiry = Enquiry::create([
            'name'         => 'Lead',
            'email'        => 'lead@example.com',
            'subject'      => 'Test ride',
            'enquiry_type' => 'test_drive',
            'message'      => 'I would like to book a test ride.',
        ]);

        $this->actingAs($agent)
            ->delete("/admin/enquiries/{$enquiry->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('enquiries', ['id' => $enquiry->id]);
    }

    // ── Account area ────────────────────────────────────────────────────────────

    public function test_customer_can_update_their_profile(): void
    {
        $customer = $this->userWithRole('customer');
        $brand = $this->brand();

        $this->actingAs($customer)
            ->put('/account/profile', [
                'name'               => 'Updated Name',
                'phone'              => '+971500000009',
                'country'            => 'Egypt',
                'city'               => 'Cairo',
                'preferred_brand_id' => $brand->id,
                'newsletter'         => '1',
            ])
            ->assertRedirect(route('account.profile'));

        $customer->refresh();
        $this->assertSame('Updated Name', $customer->name);
        $this->assertSame($brand->id, $customer->preferred_brand_id);
        $this->assertTrue($customer->newsletter_opted_in);
    }

    public function test_customer_can_change_their_password(): void
    {
        $customer = $this->userWithRole('customer');

        $this->actingAs($customer)
            ->put('/account/security', [
                'current_password'      => 'password123',
                'password'              => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ])
            ->assertRedirect(route('account.security'));

        $this->assertTrue(Hash::check('newpassword456', $customer->fresh()->password));
    }

    public function test_password_change_requires_the_current_password(): void
    {
        $customer = $this->userWithRole('customer');

        $this->actingAs($customer)
            ->put('/account/security', [
                'current_password'      => 'wrong-password',
                'password'              => 'newpassword456',
                'password_confirmation' => 'newpassword456',
            ])
            ->assertSessionHasErrors('current_password');
    }

    // ── Password reset ──────────────────────────────────────────────────────────

    public function test_guests_can_request_a_password_reset_link(): void
    {
        $this->userWithRole('customer', ['email' => 'forgot@test.com']);

        $this->get('/forgot-password')->assertStatus(200);

        $this->post('/forgot-password', ['email' => 'forgot@test.com'])
            ->assertSessionHas('success');
    }

    // ── Admin user management ───────────────────────────────────────────────────

    public function test_admin_can_filter_users_by_role(): void
    {
        $admin = $this->userWithRole('admin');
        $this->userWithRole('customer');
        $this->userWithRole('sales_agent');

        $response = $this->actingAs($admin)->get('/admin/users?role=customer');

        $response->assertStatus(200);
        $response->assertSee('Test customer');
        $response->assertDontSee('Test sales_agent');
    }

    public function test_admin_can_suspend_and_reactivate_a_user(): void
    {
        $admin = $this->userWithRole('admin');
        $customer = $this->userWithRole('customer');

        $this->actingAs($admin)
            ->patch("/admin/users/{$customer->id}/toggle-active")
            ->assertRedirect();

        $this->assertFalse($customer->fresh()->is_active);

        $this->actingAs($admin)
            ->patch("/admin/users/{$customer->id}/toggle-active")
            ->assertRedirect();

        $this->assertTrue($customer->fresh()->is_active);
    }

    public function test_admin_cannot_suspend_their_own_account(): void
    {
        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)
            ->patch("/admin/users/{$admin->id}/toggle-active")
            ->assertStatus(403);

        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_last_active_admin_cannot_deactivate_themselves_via_the_edit_form(): void
    {
        $admin = $this->userWithRole('admin');
        $adminRole = Role::where('slug', 'admin')->first();

        $this->actingAs($admin)
            ->put("/admin/users/{$admin->id}", [
                'name'      => $admin->name,
                'email'     => $admin->email,
                'role_id'   => $adminRole->id,
                'is_active' => '0',
            ])
            ->assertStatus(403);

        $this->assertTrue($admin->fresh()->is_active);
    }
}
