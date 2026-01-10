<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_view_cars(): void
    {
        Car::factory()->count(3)->create();

        $response = $this->getJson('/api/cars');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_anyone_can_view_single_car(): void
    {
        $car = Car::factory()->create(['name' => 'Avanza']);

        $response = $this->getJson("/api/cars/{$car->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Avanza',
                ],
            ]);
    }

    public function test_returns_404_for_nonexistent_car(): void
    {
        $response = $this->getJson('/api/cars/99999');

        $response->assertStatus(404);
    }

    public function test_can_filter_cars_by_category(): void
    {
        $category1 = Category::factory()->create(['name' => 'SUV']);
        $category2 = Category::factory()->create(['name' => 'Sedan']);

        Car::factory()->count(2)->create(['category_id' => $category1->id]);
        Car::factory()->count(3)->create(['category_id' => $category2->id]);

        $response = $this->getJson("/api/cars?category_id={$category1->id}");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_can_search_cars_by_name(): void
    {
        Car::factory()->create(['name' => 'Avanza']);
        Car::factory()->create(['name' => 'Innova']);
        Car::factory()->create(['name' => 'Fortuner']);

        $response = $this->getJson('/api/cars?search=Avanza');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }

    public function test_admin_can_create_car(): void
    {
        $admin = User::factory()->admin()->create();
        $token = auth()->guard('api')->login($admin);
        $category = Category::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cars', [
                'category_id' => $category->id,
                'name' => 'Avanza',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 1234 ABC',
                'daily_rent_price' => 350000,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('cars', [
            'name' => 'Avanza',
            'brand' => 'Toyota',
        ]);
    }

    public function test_admin_can_create_car_with_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $token = auth()->guard('api')->login($admin);
        $category = Category::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cars', [
                'category_id' => $category->id,
                'name' => 'Avanza',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 1234 XYZ',
                'daily_rent_price' => 350000,
                'image' => UploadedFile::fake()->image('car.jpg'),
            ]);

        $response->assertStatus(201);
    }

    public function test_customer_cannot_create_car(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $token = auth()->guard('api')->login($customer);
        $category = Category::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cars', [
                'category_id' => $category->id,
                'name' => 'Avanza',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 1234 ABC',
                'daily_rent_price' => 350000,
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_car(): void
    {
        $admin = User::factory()->admin()->create();
        $token = auth()->guard('api')->login($admin);
        $car = Car::factory()->create(['name' => 'Old Name']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/cars/{$car->id}", [
                'name' => 'New Name',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cars', ['name' => 'New Name']);
    }

    public function test_admin_can_delete_car(): void
    {
        $admin = User::factory()->admin()->create();
        $token = auth()->guard('api')->login($admin);
        $car = Car::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/cars/{$car->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('cars', ['id' => $car->id]);
    }

    public function test_cannot_create_car_with_duplicate_plate_number(): void
    {
        $admin = User::factory()->admin()->create();
        $token = auth()->guard('api')->login($admin);
        $category = Category::factory()->create();

        Car::factory()->create(['plate_number' => 'B 1234 ABC']);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cars', [
                'category_id' => $category->id,
                'name' => 'Avanza',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 1234 ABC',
                'daily_rent_price' => 350000,
            ]);

        $response->assertStatus(422);
    }
}
