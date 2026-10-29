<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Motorcycle;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function seedRoles(): void
    {
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        Role::firstOrCreate(['slug' => 'data_entry'], ['name' => 'Data Entry']);
    }

    private function makeUser(string $roleSlug, string $email = 'user@test.com'): User
    {
        $this->seedRoles();

        $user = User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => Hash::make('password'),
        ]);

        $user->roles()->attach(Role::where('slug', $roleSlug)->first());

        return $user;
    }

    private function makeBrand(): Brand
    {
        return Brand::create([
            'name' => 'Brand ' . uniqid(),
            'slug' => 'brand-' . uniqid(),
            'description' => 'Test brand',
            'is_published' => true,
        ]);
    }

    private function makeMotorcycle(Brand $brand): Motorcycle
    {
        return Motorcycle::create([
            'brand_id' => $brand->id,
            'model_name' => 'Moto ' . uniqid(),
            'slug' => 'moto-' . uniqid(),
            'description' => 'Test motorcycle',
            'engine_capacity' => 900,
            'horsepower' => 120,
            'torque' => 95,
            'year' => 2025,
            'category' => 'sport',
            'is_published' => true,
        ]);
    }

    public function test_user_can_logout_and_session_is_invalidated(): void
    {
        $admin = $this->makeUser('admin', 'admin-logout@test.com');

        $this->actingAs($admin)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_data_entry_cannot_create_motorcycle_for_unassigned_brand(): void
    {
        $assigned = $this->makeBrand();
        $unassigned = $this->makeBrand();
        $user = $this->makeUser('data_entry', 'de-create@test.com');
        $user->brands()->attach($assigned->id, ['role' => 'data_entry']);

        $response = $this->actingAs($user)->post('/admin/motorcycles', [
            'brand_id' => $unassigned->id,
            'model_name' => 'Hacked Bike',
            'description' => 'Should not be created by unassigned user.',
            'engine_capacity' => 1000,
            'horsepower' => 150,
            'torque' => 100,
            'year' => 2025,
            'category' => 'sport',
        ]);

        $response->assertSessionHasErrors('brand_id');
        $this->assertDatabaseMissing('motorcycles', ['model_name' => 'Hacked Bike']);
    }

    public function test_data_entry_can_create_motorcycle_for_assigned_brand(): void
    {
        $brand = $this->makeBrand();
        $user = $this->makeUser('data_entry', 'de-ok@test.com');
        $user->brands()->attach($brand->id, ['role' => 'data_entry']);

        $response = $this->actingAs($user)->post('/admin/motorcycles', [
            'brand_id' => $brand->id,
            'model_name' => 'Allowed Bike',
            'description' => 'Created for an assigned brand.',
            'engine_capacity' => 1000,
            'horsepower' => 150,
            'torque' => 100,
            'year' => 2025,
            'category' => 'sport',
            'is_published' => '1',
        ]);

        $response->assertRedirect('/admin/motorcycles');
        $this->assertDatabaseHas('motorcycles', ['model_name' => 'Allowed Bike']);
    }

    public function test_removing_brand_assignment_revokes_motorcycle_access(): void
    {
        $brand = $this->makeBrand();
        $moto = $this->makeMotorcycle($brand);
        $user = $this->makeUser('data_entry', 'de-revoke@test.com');
        $user->brands()->attach($brand->id, ['role' => 'data_entry']);

        $this->actingAs($user)
            ->get("/admin/motorcycles/{$moto->id}/edit")
            ->assertStatus(200);

        $user->brands()->detach($brand->id);

        $this->actingAs($user)
            ->get("/admin/motorcycles/{$moto->id}/edit")
            ->assertStatus(403);
    }

    public function test_admin_cannot_delete_brand_with_motorcycles(): void
    {
        $admin = $this->makeUser('admin', 'admin-del@test.com');
        $brand = $this->makeBrand();
        $this->makeMotorcycle($brand);

        $this->actingAs($admin)
            ->delete("/admin/brands/{$brand->id}")
            ->assertRedirect('/admin/brands');

        $this->assertDatabaseHas('brands', ['id' => $brand->id]);
    }

    public function test_data_entry_cannot_assign_brands(): void
    {
        $brand = $this->makeBrand();
        $user = $this->makeUser('data_entry', 'de-assign@test.com');
        $user->brands()->attach($brand->id, ['role' => 'data_entry']);

        $this->actingAs($user)
            ->get("/admin/brands/{$brand->id}/assign-users")
            ->assertStatus(403);
    }
}
