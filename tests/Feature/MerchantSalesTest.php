<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MerchantSalesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup roles
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'Data Entry', 'slug' => 'data_entry']);
        Role::create(['name' => 'Customer', 'slug' => 'customer']);
    }

    public function test_merchant_order_attribution_and_isolation()
    {
        // 1. Create two merchants
        $merchantA = User::factory()->create();
        $merchantA->roles()->attach(Role::where('slug', 'data_entry')->first());
        
        $merchantB = User::factory()->create();
        $merchantB->roles()->attach(Role::where('slug', 'data_entry')->first());

        // 2. Create brands and assign them
        $brandA = Brand::create(['name' => 'Brand A', 'slug' => 'brand-a', 'is_published' => true, 'description' => 'Desc A']);
        $brandB = Brand::create(['name' => 'Brand B', 'slug' => 'brand-b', 'is_published' => true, 'description' => 'Desc B']);
        
        $merchantA->brands()->attach($brandA, ['role' => 'data_entry']);
        $merchantB->brands()->attach($brandB, ['role' => 'data_entry']);

        // 3. Merchant A creates a motorcycle
        $motoA = Motorcycle::create([
            'brand_id' => $brandA->id,
            'created_by' => $merchantA->id,
            'model_name' => 'Moto A',
            'slug' => 'moto-a',
            'price' => 1000,
            'is_published' => true,
            'availability_status' => 'available',
            'description' => 'Desc Moto A',
            'short_description' => 'Short Desc A',
        ]);

        // 4. Merchant B creates a motorcycle
        $motoB = Motorcycle::create([
            'brand_id' => $brandB->id,
            'created_by' => $merchantB->id,
            'model_name' => 'Moto B',
            'slug' => 'moto-b',
            'price' => 2000,
            'is_published' => true,
            'availability_status' => 'available',
            'description' => 'Desc Moto B',
            'short_description' => 'Short Desc B',
        ]);

        // 5. Verify shop visibility
        $this->get('/motorcycles')->assertSee('Moto A')->assertSee('Moto B');

        // 6. Customer buys both
        $customer = User::factory()->create();
        $customer->roles()->attach(Role::where('slug', 'customer')->first());

        $this->actingAs($customer)
            ->post(route('cart.add', $motoA));
        $this->actingAs($customer)
            ->post(route('cart.add', $motoB));

        $idempotencyKey = \Illuminate\Support\Str::uuid()->toString();
        session(['cart_submission_token' => $idempotencyKey]);

        $response = $this->actingAs($customer)->post(route('cart.place-order'), [
            'name' => 'Customer X',
            'email' => 'customer@example.com',
            'phone' => '123456789',
            'idempotency_key' => $idempotencyKey,
            'payment_method' => 'cash_on_delivery',
        ]);

        $response->assertRedirect(route('cart.order-success'));

        // 7. Verify order persistence
        $this->assertDatabaseHas('orders', ['name' => 'Customer X', 'total_amount' => 3000]);
        $this->assertDatabaseCount('order_items', 2);
        
        $order = Order::first();
        $this->assertEquals(2, $order->items()->count());

        // 8. Verify Merchant A sees ONLY their item
        $this->actingAs($merchantA)
            ->get(route('admin.sales.index'))
            ->assertSee($order->order_reference)
            ->assertSee('Moto A')
            ->assertDontSee('Moto B');

        // 9. Verify Merchant B sees ONLY their item
        $this->actingAs($merchantB)
            ->get(route('admin.sales.index'))
            ->assertSee($order->order_reference)
            ->assertSee('Moto B')
            ->assertDontSee('Moto A');

        // 10. Verify Admin sees BOTH
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('slug', 'admin')->first());
        
        $this->actingAs($admin)
            ->get(route('admin.sales.index'))
            ->assertSee($order->order_reference)
            ->assertSee('Moto A')
            ->assertSee('Moto B');
            
        // 11. Test Security: Merchant A cannot view Merchant B's item details in a direct request if they were separate (here they share an order but we filter)
        // Already verified by the view tests above
    }
}
