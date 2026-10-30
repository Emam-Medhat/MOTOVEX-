<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Role;
use App\Models\SparePart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Spare Parts multi-merchant marketplace workflow.
 *
 * Covers the required scenarios:
 *   A — single-merchant purchase
 *   B — multi-merchant purchase (one canonical order)
 *   C — security / authorization
 *   D — inventory safety
 *   E — fulfillment status workflow
 */
class SparePartsWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['admin', 'data_entry', 'sales_agent', 'customer', 'merchant'] as $slug) {
            Role::firstOrCreate(['slug' => $slug], ['name' => ucfirst($slug)]);
        }
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function role(string $slug): Role
    {
        return Role::where('slug', $slug)->firstOrFail();
    }

    private function admin(): User
    {
        $user = User::factory()->create(['account_status' => 'active']);
        $user->roles()->attach($this->role('admin'));

        return $user;
    }

    private function merchant(string $name): User
    {
        $user = User::factory()->create([
            'name'           => $name,
            'account_status' => 'active',
            'is_active'      => true,
        ]);
        $user->roles()->attach($this->role('merchant'));

        return $user;
    }

    private function customer(string $name = 'Customer X'): User
    {
        $user = User::factory()->create([
            'name'           => $name,
            'account_status' => 'active',
            'is_active'      => true,
        ]);
        $user->roles()->attach($this->role('customer'));

        return $user;
    }

    private function brand(string $name): Brand
    {
        return Brand::create([
            'name'         => $name,
            'slug'         => Str::slug($name),
            'description'  => $name . ' description',
            'is_published' => true,
        ]);
    }

    private function sparePart(User $merchant, array $overrides = []): SparePart
    {
        $name = $overrides['name'] ?? ('Part ' . Str::random(5));

        return SparePart::create(array_merge([
            'created_by'          => $merchant->id,
            'name'                => $name,
            'slug'                => Str::slug($name) . '-' . Str::random(4),
            'sku'                 => 'SKU-' . strtoupper(Str::random(5)),
            'category'            => 'engine',
            'description'         => 'Test spare part',
            'price'               => 100,
            'currency'            => 'USD',
            'stock_quantity'      => 10,
            'availability_status' => SparePart::AVAIL_IN_STOCK,
            'is_published'        => true,
        ], $overrides));
    }

    /** @param array<int, array{0: SparePart, 1: int}> $parts */
    private function placeOrder(User $customer, array $parts): Order
    {
        foreach ($parts as [$part, $qty]) {
            $this->actingAs($customer)
                ->post(route('cart.add-part', $part), ['quantity' => $qty])
                ->assertRedirect();
        }

        $this->actingAs($customer)->post(route('cart.place-order'), [
            'name'             => $customer->name,
            'email'            => $customer->email,
            'phone'            => '01000000000',
            'delivery_method'  => 'delivery',
            'delivery_address' => '123 Test Street',
            'city'             => 'Cairo',
            'payment_method'   => 'cash_on_delivery',
        ])->assertRedirect(route('cart.order-success'));

        return Order::latest('id')->firstOrFail();
    }

    // ── Cart & Checkout pages render spare parts ─────────────────────────────

    public function test_cart_and_checkout_pages_render_spare_parts(): void
    {
        $merchant = $this->merchant('Merchant A');
        $customer = $this->customer();

        $part = $this->sparePart($merchant, ['name' => 'Air Filter', 'price' => 75, 'stock_quantity' => 10]);

        $this->actingAs($customer)
            ->post(route('cart.add-part', $part), ['quantity' => 2])
            ->assertRedirect();

        // Cart page renders the spare part (and does not 500 on the new structure)
        $this->actingAs($customer)
            ->get(route('cart.index'))
            ->assertOk()
            ->assertSee('Air Filter')
            ->assertSee('2');

        // Checkout page renders the spare part in the order summary
        $this->actingAs($customer)
            ->get(route('cart.checkout'))
            ->assertOk()
            ->assertSee('Air Filter')
            ->assertSee('150'); // 75 × 2

        // Quantity update is validated server-side
        $this->actingAs($customer)
            ->patch(route('cart.update-part', $part), ['quantity' => 4])
            ->assertRedirect();

        $this->assertEquals(4, session('cart.spare_parts.' . $part->id . '.quantity'));
    }

    // ── TEST A — Single Merchant Purchase ────────────────────────────────────

    public function test_a_single_merchant_purchase_flow(): void
    {
        $merchantA = $this->merchant('Merchant A');
        $merchantB = $this->merchant('Merchant B');
        $customer  = $this->customer();
        $admin     = $this->admin();

        $part = $this->sparePart($merchantA, ['name' => 'Chain Kit', 'price' => 150]);

        $order = $this->placeOrder($customer, [[$part, 2]]);

        // One canonical order + one item attributed to Merchant A
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);

        $item = $order->items()->first();
        $this->assertEquals($merchantA->id, $item->merchant_id);
        $this->assertEquals(OrderItem::TYPE_SPARE_PART, $item->product_type);
        $this->assertEquals(300, (float) $order->total_amount);

        // Merchant A sees it
        $this->actingAs($merchantA)
            ->get(route('account.merchant-orders.index'))
            ->assertOk()
            ->assertSee($order->order_reference)
            ->assertSee('Chain Kit');

        // Merchant B does not
        $this->actingAs($merchantB)
            ->get(route('account.merchant-orders.index'))
            ->assertOk()
            ->assertDontSee($order->order_reference);

        // Admin sees the complete order
        $this->actingAs($admin)
            ->get(route('admin.sales.show', $order))
            ->assertOk()
            ->assertSee($order->order_reference)
            ->assertSee('Chain Kit');

        // Customer sees the complete order
        $this->actingAs($customer)
            ->get(route('account.orders.show', $order))
            ->assertOk()
            ->assertSee($order->order_reference)
            ->assertSee('Chain Kit');
    }

    // ── TEST B — Multiple Merchant Purchase ──────────────────────────────────

    public function test_b_multiple_merchant_single_canonical_order(): void
    {
        $merchantA = $this->merchant('Merchant A');
        $merchantB = $this->merchant('Merchant B');
        $customer  = $this->customer();
        $admin     = $this->admin();

        $partA = $this->sparePart($merchantA, ['name' => 'Brake Disc A', 'price' => 100]);
        $partB = $this->sparePart($merchantB, ['name' => 'Spark Plug B', 'price' => 200]);

        $order = $this->placeOrder($customer, [[$partA, 1], [$partB, 1]]);

        // Exactly one order, no duplicate payments
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 2);
        $this->assertEquals(300, (float) $order->total_amount);

        // Merchant A sees only their item
        $this->actingAs($merchantA)
            ->get(route('account.merchant-orders.index'))
            ->assertOk()
            ->assertSee('Brake Disc A')
            ->assertDontSee('Spark Plug B');

        // Merchant B sees only their item
        $this->actingAs($merchantB)
            ->get(route('account.merchant-orders.index'))
            ->assertOk()
            ->assertSee('Spark Plug B')
            ->assertDontSee('Brake Disc A');

        // Admin sees both
        $this->actingAs($admin)
            ->get(route('admin.sales.show', $order))
            ->assertOk()
            ->assertSee('Brake Disc A')
            ->assertSee('Spark Plug B');

        // Customer sees both
        $this->actingAs($customer)
            ->get(route('account.orders.show', $order))
            ->assertOk()
            ->assertSee('Brake Disc A')
            ->assertSee('Spark Plug B');

        // No duplicate payment record
        $this->assertEquals(1, Order::where('order_reference', $order->order_reference)->count());
    }

    // ── TEST C — Security ────────────────────────────────────────────────────

    public function test_c_merchant_isolation_and_customer_privacy(): void
    {
        $merchantA = $this->merchant('Merchant A');
        $merchantB = $this->merchant('Merchant B');
        $customer  = $this->customer('Customer One');
        $other     = $this->customer('Customer Two');

        $partA = $this->sparePart($merchantA, ['name' => 'Exclusive Part A']);
        $order = $this->placeOrder($customer, [[$partA, 1]]);
        $item  = $order->items()->first();

        // 1. Merchant B cannot view Merchant A's item (URL tampering)
        $this->actingAs($merchantB)
            ->get(route('account.merchant-orders.show', $item))
            ->assertForbidden();

        // 2. Merchant B cannot update Merchant A's item status
        $this->actingAs($merchantB)
            ->patch(route('account.merchant-orders.update-status', $item), ['status' => OrderItem::STATUS_ACCEPTED])
            ->assertForbidden();

        $this->assertEquals(OrderItem::STATUS_PENDING, $item->fresh()->status);

        // 3. Merchant B cannot modify Merchant A's spare part (owner cannot be hijacked)
        $this->actingAs($merchantB)
            ->put(route('account.spare-parts.update', $partA), [
                'name' => 'Hijacked',
                'price' => 1,
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('spare_parts', [
            'id'         => $partA->id,
            'created_by' => $merchantA->id,
            'name'       => 'Exclusive Part A',
        ]);

        // 4. Customer cannot access another customer's order
        $this->actingAs($other)
            ->get(route('account.orders.show', $order))
            ->assertForbidden();

        // 5. Guest cannot reach account routes
        auth()->logout();
        $this->get(route('account.orders'))->assertRedirect(route('login'));
    }

    // ── TEST D — Inventory ───────────────────────────────────────────────────

    public function test_d_inventory_is_decremented_once_and_protected(): void
    {
        $merchant = $this->merchant('Merchant A');
        $customer = $this->customer();

        $part = $this->sparePart($merchant, ['name' => 'Oil Filter', 'stock_quantity' => 10, 'price' => 50]);
        $key  = (string) Str::uuid();

        // Purchase 3 → stock decremented exactly once
        $this->actingAs($customer)->post(route('cart.add-part', $part), ['quantity' => 3]);
        session(['cart_submission_token' => $key]);
        $this->actingAs($customer)->post(route('cart.place-order'), [
            'name'            => $customer->name,
            'email'           => $customer->email,
            'phone'           => '01000000000',
            'idempotency_key' => $key,
            'payment_method'  => 'cash_on_delivery',
        ])->assertRedirect(route('cart.order-success'));

        $order = Order::latest('id')->firstOrFail();
        $this->assertEquals(7, $part->fresh()->stock_quantity);

        // Duplicate submission with the SAME idempotency key must not double-deduct
        $this->actingAs($customer)->post(route('cart.add-part', $part), ['quantity' => 3]);
        session(['cart_submission_token' => $key]);
        $this->actingAs($customer)->post(route('cart.place-order'), [
            'name'            => $customer->name,
            'email'           => $customer->email,
            'phone'           => '01000000000',
            'idempotency_key' => $key,
            'payment_method'  => 'cash_on_delivery',
        ])->assertRedirect(route('cart.order-success'));

        $this->assertDatabaseCount('orders', 1);
        $this->assertEquals(7, $part->fresh()->stock_quantity);

        // Insufficient stock prevents checkout
        $scarce = $this->sparePart($merchant, ['name' => 'Rare Part', 'stock_quantity' => 5]);
        $this->actingAs($customer)->post(route('cart.add-part', $scarce), ['quantity' => 5]);

        // Stock drops externally after it is already in the cart
        $scarce->update(['stock_quantity' => 2]);

        $this->actingAs($customer)->post(route('cart.place-order'), [
            'name'           => $customer->name,
            'email'          => $customer->email,
            'phone'          => '01000000000',
            'payment_method' => 'cash_on_delivery',
        ])->assertSessionHas('error');

        $this->assertDatabaseCount('orders', 1); // no new order

        // Cancellation releases stock exactly once
        $item   = $order->items()->first();
        $before = $part->fresh()->stock_quantity;

        $this->actingAs($merchant)->patch(
            route('account.merchant-orders.update-status', $item),
            ['status' => OrderItem::STATUS_CANCELLED]
        )->assertRedirect();

        $this->assertEquals($before + 3, $part->fresh()->stock_quantity);
    }

    // ── TEST E — Status Workflow ─────────────────────────────────────────────

    public function test_e_fulfillment_status_workflow_and_guards(): void
    {
        $merchant = $this->merchant('Merchant A');
        $customer = $this->customer();
        $admin    = $this->admin();

        $part  = $this->sparePart($merchant, ['name' => 'Exhaust Pipe', 'price' => 400]);
        $order = $this->placeOrder($customer, [[$part, 1]]);
        $item  = $order->items()->first();

        // Invalid transition: pending → shipped must be rejected
        $this->actingAs($merchant)->patch(
            route('account.merchant-orders.update-status', $item),
            ['status' => OrderItem::STATUS_SHIPPED]
        )->assertSessionHas('error');

        $this->assertEquals(OrderItem::STATUS_PENDING, $item->fresh()->status);

        // Valid forward workflow
        foreach ([
            OrderItem::STATUS_ACCEPTED,
            OrderItem::STATUS_PREPARING,
            OrderItem::STATUS_READY,
            OrderItem::STATUS_SHIPPED,
            OrderItem::STATUS_DELIVERED,
        ] as $status) {
            $this->actingAs($merchant)->patch(
                route('account.merchant-orders.update-status', $item),
                ['status' => $status]
            )->assertRedirect();

            $this->assertEquals($status, $item->fresh()->status);
        }

        // Customer and admin see the final state
        $this->actingAs($customer)
            ->get(route('account.orders.show', $order))
            ->assertOk()
            ->assertSee('Delivered');

        $this->actingAs($admin)
            ->get(route('admin.sales.show', $order))
            ->assertOk()
            ->assertSee('Delivered');
    }

    // ── Notifications ────────────────────────────────────────────────────────

    public function test_merchants_and_customers_are_notified_without_duplicates(): void
    {
        $merchantA = $this->merchant('Merchant A');
        $merchantB = $this->merchant('Merchant B');
        $customer  = $this->customer();

        $partA = $this->sparePart($merchantA, ['name' => 'Notif Part A']);
        $partB = $this->sparePart($merchantB, ['name' => 'Notif Part B']);

        $order = $this->placeOrder($customer, [[$partA, 1], [$partB, 1]]);

        // Each merchant gets exactly one notification for the order
        $this->assertEquals(1, $merchantA->notifications()->count());
        $this->assertEquals(1, $merchantB->notifications()->count());

        // Customer gets notified when an item status changes
        $item = $order->items()->where('merchant_id', $merchantA->id)->first();

        $this->actingAs($merchantA)->patch(
            route('account.merchant-orders.update-status', $item),
            ['status' => OrderItem::STATUS_ACCEPTED]
        );

        $this->assertEquals(1, $customer->notifications()->count());
    }
}
