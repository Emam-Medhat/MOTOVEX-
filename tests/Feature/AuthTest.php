<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function createRole(string $slug, string $name): Role
    {
        return Role::create(['name' => $name, 'slug' => $slug]);
    }

    private function createAdmin(): User
    {
        $user = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@test.com',
            'password' => Hash::make('password'),
        ]);
        $user->roles()->attach($this->createRole('admin', 'Admin'));
        return $user;
    }

    private function createDataEntry(?Brand $brand = null): User
    {
        $user = User::create([
            'name'     => 'Data Entry',
            'email'    => 'de@test.com',
            'password' => Hash::make('password'),
        ]);
        $role = Role::firstOrCreate(['slug' => 'data_entry'], ['name' => 'Data Entry']);
        $user->roles()->attach($role);

        if ($brand) {
            $user->brands()->attach($brand->id, ['role' => 'data_entry']);
        }

        return $user;
    }

    private function createBrand(bool $published = true): Brand
    {
        return Brand::create([
            'name'         => 'Test Brand ' . uniqid(),
            'slug'         => 'test-brand-' . uniqid(),
            'description'  => 'A test brand',
            'is_published' => $published,
        ]);
    }

    private function createMotorcycle(Brand $brand, bool $published = true): Motorcycle
    {
        return Motorcycle::create([
            'brand_id'        => $brand->id,
            'model_name'      => 'Test Moto ' . uniqid(),
            'slug'            => 'test-moto-' . uniqid(),
            'description'     => 'A test motorcycle',
            'engine_capacity' => 1000,
            'horsepower'      => 100,
            'torque'          => 100,
            'year'            => 2024,
            'category'        => 'sport',
            'is_published'    => $published,
        ]);
    }

    // ── Guest access ────────────────────────────────────────────────────────────

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_brands(): void
    {
        $response = $this->get('/admin/brands');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_motorcycles(): void
    {
        $response = $this->get('/admin/motorcycles');
        $response->assertRedirect('/login');
    }

    public function test_guest_can_access_public_home(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_guest_can_view_login_form(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    // ── Login ────────────────────────────────────────────────────────────────────

    public function test_user_can_login_with_correct_credentials(): void
    {
        $admin = $this->createAdmin();

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($admin);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $this->createAdmin();

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // ── Admin access ─────────────────────────────────────────────────────────────

    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->createAdmin();
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_admin_can_view_all_brands(): void
    {
        $admin = $this->createAdmin();
        $this->createBrand();

        $response = $this->actingAs($admin)->get('/admin/brands');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_brand(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post('/admin/brands', [
            'name'         => 'New Brand',
            'description'  => 'A new brand description',
            'is_published' => '1',
        ]);

        $response->assertRedirect('/admin/brands');
        $this->assertDatabaseHas('brands', ['name' => 'New Brand']);
    }

    public function test_admin_can_delete_brand(): void
    {
        $admin = $this->createAdmin();
        $brand = $this->createBrand();

        $response = $this->actingAs($admin)->delete("/admin/brands/{$brand->id}");
        $response->assertRedirect('/admin/brands');
        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    public function test_admin_can_manage_any_motorcycle(): void
    {
        $admin = $this->createAdmin();
        $brand = $this->createBrand();
        $moto  = $this->createMotorcycle($brand);

        $response = $this->actingAs($admin)->get("/admin/motorcycles/{$moto->id}/edit");
        $response->assertStatus(200);
    }

    // ── Data Entry access ────────────────────────────────────────────────────────

    public function test_data_entry_can_access_dashboard(): void
    {
        $brand = $this->createBrand();
        // Need admin role created first for de user creation
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $de = $this->createDataEntry($brand);

        $response = $this->actingAs($de)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_data_entry_can_manage_assigned_brand_motorcycle(): void
    {
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $brand = $this->createBrand();
        $de    = $this->createDataEntry($brand);
        $moto  = $this->createMotorcycle($brand);

        $response = $this->actingAs($de)->get("/admin/motorcycles/{$moto->id}/edit");
        $response->assertStatus(200);
    }

    public function test_data_entry_cannot_access_unassigned_brand_motorcycle(): void
    {
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $assignedBrand   = $this->createBrand();
        $unassignedBrand = $this->createBrand();
        $de   = $this->createDataEntry($assignedBrand);
        $moto = $this->createMotorcycle($unassignedBrand);

        $response = $this->actingAs($de)->get("/admin/motorcycles/{$moto->id}/edit");
        $response->assertStatus(403);
    }

    public function test_data_entry_cannot_create_brand(): void
    {
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $brand = $this->createBrand();
        $de    = $this->createDataEntry($brand);

        $response = $this->actingAs($de)->post('/admin/brands', [
            'name'        => 'Hacked Brand',
            'description' => 'Should not be created',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('brands', ['name' => 'Hacked Brand']);
    }

    public function test_data_entry_cannot_delete_brand(): void
    {
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $brand = $this->createBrand();
        $de    = $this->createDataEntry($brand);

        $response = $this->actingAs($de)->delete("/admin/brands/{$brand->id}");
        $response->assertStatus(403);
        $this->assertDatabaseHas('brands', ['id' => $brand->id]);
    }

    public function test_data_entry_cannot_access_users_section(): void
    {
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $brand = $this->createBrand();
        $de    = $this->createDataEntry($brand);

        $response = $this->actingAs($de)->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_data_entry_cannot_access_enquiries(): void
    {
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        $brand = $this->createBrand();
        $de    = $this->createDataEntry($brand);

        $response = $this->actingAs($de)->get('/admin/enquiries');
        $response->assertStatus(403);
    }

    // ── Public pages show only published content ─────────────────────────────────

    public function test_public_brands_page_shows_only_published(): void
    {
        $this->createBrand(true);   // published
        $this->createBrand(false);  // draft

        $response = $this->get('/brands');
        $response->assertStatus(200);

        // Only 1 published brand should appear
        $this->assertEquals(1, \App\Models\Brand::where('is_published', true)->count());
    }

    public function test_unpublished_brand_returns_404(): void
    {
        $brand = $this->createBrand(false);

        $response = $this->get("/brands/{$brand->slug}");
        $response->assertStatus(404);
    }

    public function test_unpublished_motorcycle_returns_404(): void
    {
        $brand = $this->createBrand(true);
        $moto  = $this->createMotorcycle($brand, false);

        $response = $this->get("/motorcycles/{$moto->slug}");
        $response->assertStatus(404);
    }

    // ── Contact form validation ───────────────────────────────────────────────────

    public function test_contact_form_rejects_empty_submission(): void
    {
        $response = $this->post('/contact', []);
        $response->assertSessionHasErrors(['name', 'email', 'subject', 'enquiry_type', 'message']);
    }

    public function test_contact_form_rejects_invalid_email(): void
    {
        $response = $this->post('/contact', [
            'name'         => 'Test User',
            'email'        => 'not-an-email',
            'subject'      => 'Test',
            'enquiry_type' => 'general',
            'message'      => 'This is a test message that is long enough.',
        ]);
        $response->assertSessionHasErrors('email');
    }

    public function test_contact_form_stores_valid_enquiry(): void
    {
        $response = $this->post('/contact', [
            'name'         => 'John Rider',
            'email'        => 'john@example.com',
            'subject'      => 'Test Drive Request',
            'enquiry_type' => 'test_drive',
            'message'      => 'I would like to book a test ride for the Ducati Panigale.',
        ]);

        $response->assertRedirect('/contact');
        $this->assertDatabaseHas('enquiries', ['email' => 'john@example.com']);
    }
}
