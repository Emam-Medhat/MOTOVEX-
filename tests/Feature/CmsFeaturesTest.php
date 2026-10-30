<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\Promotion;
use App\Models\Role;
use App\Models\Showroom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'Data Entry', 'slug' => 'data_entry']);
        Role::create(['name' => 'Customer', 'slug' => 'customer']);
    }

    /** @test */
    public function admin_can_access_promotions_index()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('slug', 'admin')->first());

        $response = $this->actingAs($admin)->get(route('admin.promotions.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_showrooms_index()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('slug', 'admin')->first());

        $response = $this->actingAs($admin)->get(route('admin.showrooms.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_can_view_cart()
    {
        $response = $this->get(route('cart.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function guest_can_add_to_cart()
    {
        $brand = Brand::create([
            'name' => 'Test Brand',
            'slug' => 'test-brand',
            'description' => 'Test description',
            'is_published' => true
        ]);

        $bike = Motorcycle::create([
            'brand_id' => $brand->id,
            'model_name' => 'Test Bike',
            'slug' => 'test-bike',
            'description' => 'Test bike description',
            'engine_capacity' => 1000,
            'horsepower' => 150,
            'torque' => 100,
            'year' => 2024,
            'category' => 'Sport',
            'price' => 1000,
            'is_published' => true
        ]);

        $response = $this->post(route('cart.add', $bike->slug));
        $response->assertRedirect();
        // Cart session is structured as ['motorcycles' => [...], 'spare_parts' => [...]].
        $this->assertEquals(1, count(session('cart.motorcycles', [])));
        $this->assertArrayHasKey($bike->id, session('cart.motorcycles', []));
    }

    /** @test */
    public function admin_can_create_promotion()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('slug', 'admin')->first());

        $brand = Brand::create(['name' => 'Test Brand', 'slug' => 'test-brand', 'description' => 'Test description', 'is_published' => true]);

        $response = $this->actingAs($admin)->post(route('admin.promotions.store'), [
            'brand_id' => $brand->id,
            'title' => 'New Promo',
            'slug' => 'new-promo',
            'description' => 'Test description',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('promotions', ['title' => 'New Promo']);
    }

    /** @test */
    public function admin_can_create_showroom()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('slug', 'admin')->first());

        $brand = Brand::create(['name' => 'Test Brand', 'slug' => 'test-brand', 'description' => 'Test description', 'is_published' => true]);

        $response = $this->actingAs($admin)->post(route('admin.showrooms.store'), [
            'brand_id' => $brand->id,
            'name' => 'Main Showroom',
            'slug' => 'main-showroom',
            'location' => 'City Center',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('showrooms', ['name' => 'Main Showroom']);
    }
}
