<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_review(): void
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);
        $car = Car::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/reviews', [
                'car_id' => $car->id,
                'rating' => 5,
                'comment' => 'Mobil sangat bagus dan nyaman!',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'car_id' => $car->id,
            'rating' => 5,
        ]);
    }

    public function test_unauthenticated_user_cannot_create_review(): void
    {
        $car = Car::factory()->create();

        $response = $this->postJson('/api/reviews', [
            'car_id' => $car->id,
            'rating' => 5,
            'comment' => 'Great car!',
        ]);

        $response->assertStatus(401);
    }

    public function test_car_detail_includes_reviews(): void
    {
        $car = Car::factory()->create();
        Review::factory()->count(3)->create(['car_id' => $car->id]);

        $response = $this->getJson("/api/cars/{$car->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'reviews',
                ],
            ]);
    }
}
