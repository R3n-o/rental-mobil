<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_view_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->admin()->create();
        $token = auth()->guard('api')->login($admin);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/categories', [
                'name' => 'SUV',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['name' => 'SUV']);
    }

    public function test_customer_cannot_create_category(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $token = auth()->guard('api')->login($customer);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/categories', [
                'name' => 'SUV',
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_category(): void
    {
        $admin = User::factory()->admin()->create();
        $token = auth()->guard('api')->login($admin);
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/categories/{$category->id}", [
                'name' => 'New Name',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', ['name' => 'New Name']);
    }

    public function test_admin_can_delete_category(): void
    {
        $admin = User::factory()->admin()->create();
        $token = auth()->guard('api')->login($admin);
        $category = Category::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_unauthenticated_user_cannot_create_category(): void
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'SUV',
        ]);

        $response->assertStatus(401);
    }
}
