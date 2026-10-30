<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\Promotion;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Covers the two deliverables:
 *  1. The public Services page is database-driven with full Admin CRUD.
 *  2. The public Promotions page exposes only live (active + in-window) promos.
 */
class ServicesPromotionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        Role::firstOrCreate(['slug' => 'data_entry'], ['name' => 'Data Entry']);
        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('slug', 'admin')->first());

        return $user;
    }

    private function makeService(array $overrides = []): Service
    {
        return Service::create(array_merge([
            'title'        => 'Test Ride Booking',
            'slug'         => 'test-ride-' . uniqid(),
            'description'  => 'Book a test ride today.',
            'is_active'    => true,
            'sort_order'   => 1,
        ], $overrides));
    }

    private function makeBrand(): Brand
    {
        return Brand::create([
            'name'         => 'Brand ' . uniqid(),
            'slug'         => 'brand-' . uniqid(),
            'description'  => 'Test brand',
            'is_published' => true,
        ]);
    }

    private function makeMotorcycle(Brand $brand, float $price = 10000): Motorcycle
    {
        return Motorcycle::create([
            'brand_id'        => $brand->id,
            'model_name'      => 'Moto ' . uniqid(),
            'slug'            => 'moto-' . uniqid(),
            'description'     => 'Test motorcycle',
            'engine_capacity' => 900,
            'horsepower'      => 120,
            'torque'          => 95,
            'year'            => 2025,
            'category'        => 'sport',
            'price'           => $price,
            'currency'        => 'USD',
            'is_published'    => true,
        ]);
    }

    private function makePromotion(array $overrides = []): Promotion
    {
        return Promotion::create(array_merge([
            'title'       => 'Promo ' . uniqid(),
            'slug'        => 'promo-' . uniqid(),
            'description' => 'A great offer.',
            'is_active'   => true,
        ], $overrides));
    }

    // ─── TASK 1: Services ───────────────────────────────────────────────────────

    public function test_public_services_page_loads_active_services_from_database(): void
    {
        $this->makeService(['title' => 'Database Driven Service', 'description' => 'Loaded from DB.']);

        $this->get(route('services'))
            ->assertOk()
            ->assertSee('Database Driven Service')
            ->assertSee('Loaded from DB.');
    }

    public function test_public_services_page_hides_inactive_services(): void
    {
        $this->makeService(['title' => 'Visible Service', 'is_active' => true]);
        $this->makeService(['title' => 'Hidden Service', 'is_active' => false]);

        $this->get(route('services'))
            ->assertOk()
            ->assertSee('Visible Service')
            ->assertDontSee('Hidden Service');
    }

    public function test_public_services_page_reflects_database_changes(): void
    {
        $service = $this->makeService(['title' => 'Editable Service']);

        $this->get(route('services'))->assertSee('Editable Service');

        // Rename while still published — the public page must pick it up.
        $service->update(['title' => 'Renamed Service']);

        $this->get(route('services'))
            ->assertOk()
            ->assertSee('Renamed Service')
            ->assertDontSee('Editable Service');

        // Unpublish — it must disappear from the public page.
        $service->update(['is_active' => false]);

        $this->get(route('services'))
            ->assertOk()
            ->assertDontSee('Renamed Service');
    }

    public function test_admin_can_create_service(): void
    {
        $response = $this->actingAs($this->admin())->post(route('admin.services.store'), [
            'title'       => 'Financing Plans',
            'description' => 'Flexible financing options.',
            'is_active'   => '1',
            'sort_order'  => 2,
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', ['title' => 'Financing Plans', 'is_active' => true]);
    }

    public function test_admin_can_update_service(): void
    {
        $service = $this->makeService(['title' => 'Old Title']);

        $response = $this->actingAs($this->admin())->put(route('admin.services.update', $service), [
            'title'       => 'New Title',
            'description' => 'Updated description.',
            'is_active'   => '1',
            'sort_order'  => 5,
        ]);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', ['id' => $service->id, 'title' => 'New Title', 'sort_order' => 5]);
    }

    public function test_admin_can_toggle_service_publish_state(): void
    {
        $service = $this->makeService(['is_active' => true]);

        $this->actingAs($this->admin())
            ->patch(route('admin.services.toggle', $service))
            ->assertRedirect();

        $this->assertFalse($service->fresh()->is_active);

        $this->actingAs($this->admin())
            ->patch(route('admin.services.toggle', $service))
            ->assertRedirect();

        $this->assertTrue($service->fresh()->is_active);
    }

    public function test_admin_can_delete_service(): void
    {
        $service = $this->makeService();

        $this->actingAs($this->admin())
            ->delete(route('admin.services.destroy', $service))
            ->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_service_image_is_uploaded_securely(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())->post(route('admin.services.store'), [
            'title'       => 'Service With Image',
            'description' => 'Has an image.',
            'is_active'   => '1',
            'image'       => UploadedFile::fake()->create('service.jpg', 50, 'image/jpeg'),
        ])->assertRedirect(route('admin.services.index'));

        $service = Service::where('title', 'Service With Image')->firstOrFail();
        $this->assertNotNull($service->image_path);
        Storage::disk('public')->assertExists($service->image_path);
    }

    public function test_service_validation_rejects_missing_title(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.services.store'), ['description' => 'No title here.'])
            ->assertSessionHasErrors('title');
    }

    public function test_guest_cannot_access_admin_services(): void
    {
        $this->get(route('admin.services.index'))->assertRedirect(route('login'));
    }

    // ─── TASK 2: Promotions ─────────────────────────────────────────────────────

    public function test_public_promotions_page_loads(): void
    {
        $this->get(route('promotions'))->assertOk()->assertSee('Promotions');
    }

    public function test_public_promotions_page_shows_live_promotion(): void
    {
        $this->makePromotion([
            'title'      => 'Live Seasonal Offer',
            'is_active'  => true,
            'start_date' => now()->subDay()->toDateString(),
            'end_date'   => now()->addDay()->toDateString(),
        ]);

        $this->get(route('promotions'))
            ->assertOk()
            ->assertSee('Live Seasonal Offer');
    }

    public function test_public_promotions_page_hides_inactive_promotion(): void
    {
        $this->makePromotion(['title' => 'Inactive Offer', 'is_active' => false]);

        $this->get(route('promotions'))
            ->assertOk()
            ->assertDontSee('Inactive Offer');
    }

    public function test_public_promotions_page_hides_expired_promotion(): void
    {
        $this->makePromotion([
            'title'      => 'Expired Offer',
            'is_active'  => true,
            'start_date' => now()->subMonth()->toDateString(),
            'end_date'   => now()->subDay()->toDateString(),
        ]);

        $this->get(route('promotions'))
            ->assertOk()
            ->assertDontSee('Expired Offer');
    }

    public function test_public_promotions_page_hides_not_yet_started_promotion(): void
    {
        $this->makePromotion([
            'title'      => 'Future Offer',
            'is_active'  => true,
            'start_date' => now()->addWeek()->toDateString(),
            'end_date'   => now()->addMonth()->toDateString(),
        ]);

        $this->get(route('promotions'))
            ->assertOk()
            ->assertDontSee('Future Offer');
    }

    public function test_promotion_cta_links_to_linked_motorcycle(): void
    {
        $brand = $this->makeBrand();
        $bike  = $this->makeMotorcycle($brand);

        $this->makePromotion([
            'title'         => 'Bike Specific Offer',
            'brand_id'      => $brand->id,
            'motorcycle_id' => $bike->id,
        ]);

        $this->get(route('promotions'))
            ->assertOk()
            ->assertSee(route('motorcycles.show', $bike), false);
    }

    public function test_discounted_price_uses_percentage_discount(): void
    {
        $brand = $this->makeBrand();
        $bike  = $this->makeMotorcycle($brand, 10000);

        $promo = $this->makePromotion([
            'motorcycle_id'  => $bike->id,
            'discount_type'  => 'percentage',
            'discount_value' => 15,
        ]);

        $promo->load('motorcycle');
        $this->assertTrue($promo->hasPriceableDiscount());
        $this->assertSame(8500.0, $promo->discountedPrice());
        $this->assertSame(1500.0, $promo->savingsAmount());
    }

    public function test_discounted_price_uses_fixed_discount(): void
    {
        $brand = $this->makeBrand();
        $bike  = $this->makeMotorcycle($brand, 10000);

        $promo = $this->makePromotion([
            'motorcycle_id'  => $bike->id,
            'discount_type'  => 'fixed',
            'discount_value' => 2500,
        ]);

        $promo->load('motorcycle');
        $this->assertSame(7500.0, $promo->discountedPrice());
    }

    public function test_discounted_price_is_never_invented_without_data(): void
    {
        $brand = $this->makeBrand();
        $bike  = $this->makeMotorcycle($brand, 10000);

        // No discount fields entered by admin.
        $promo = $this->makePromotion(['motorcycle_id' => $bike->id]);
        $promo->load('motorcycle');

        $this->assertFalse($promo->hasPriceableDiscount());
        $this->assertNull($promo->discountedPrice());
        $this->assertNull($promo->savingsAmount());
    }

    public function test_admin_promotions_index_still_accessible(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.promotions.index'))
            ->assertOk();
    }

    public function test_admin_can_create_promotion_with_motorcycle_target(): void
    {
        $brand = $this->makeBrand();
        $bike  = $this->makeMotorcycle($brand);

        $this->actingAs($this->admin())->post(route('admin.promotions.store'), [
            'brand_id'       => $brand->id,
            'motorcycle_id'  => $bike->id,
            'title'          => 'New Targeted Promo',
            'description'    => 'Targets a specific bike.',
            'discount_type'  => 'percentage',
            'discount_value' => 10,
            'is_active'      => '1',
        ])->assertRedirect(route('admin.promotions.index'));

        $this->assertDatabaseHas('promotions', [
            'title'          => 'New Targeted Promo',
            'motorcycle_id'  => $bike->id,
            'discount_type'  => 'percentage',
        ]);
    }
}
