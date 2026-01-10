<?php

namespace Tests\Unit;

use App\Models\Review;
use App\Models\User;
use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_can_be_created(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create();

        $review = Review::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'rating' => 5,
            'comment' => 'Mobil sangat bagus dan nyaman!',
        ]);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'car_id' => $car->id,
            'rating' => 5,
        ]);
    }

    public function test_review_belongs_to_user(): void
    {
        $user = User::factory()->create(['name' => 'Reviewer']);
        $review = Review::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $review->user);
        $this->assertEquals('Reviewer', $review->user->name);
    }

    public function test_review_belongs_to_car(): void
    {
        $car = Car::factory()->create(['name' => 'Innova']);
        $review = Review::factory()->create(['car_id' => $car->id]);

        $this->assertInstanceOf(Car::class, $review->car);
        $this->assertEquals('Innova', $review->car->name);
    }

    public function test_review_fillable_attributes(): void
    {
        $review = new Review();
        $expected = [
            'user_id',
            'car_id',
            'rating',
            'comment'
        ];

        $this->assertEquals($expected, $review->getFillable());
    }

    public function test_review_rating_values(): void
    {
        $review1 = Review::factory()->create(['rating' => 1]);
        $review5 = Review::factory()->create(['rating' => 5]);

        $this->assertEquals(1, $review1->rating);
        $this->assertEquals(5, $review5->rating);
    }

    public function test_car_has_multiple_reviews(): void
    {
        $car = Car::factory()->create();

        Review::factory()->count(3)->create([
            'car_id' => $car->id,
        ]);

        $this->assertCount(3, $car->reviews);
    }

    public function test_user_can_review_multiple_cars(): void
    {
        $user = User::factory()->create();
        $car1 = Car::factory()->create();
        $car2 = Car::factory()->create();

        Review::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car1->id,
        ]);

        Review::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car2->id,
        ]);

        $this->assertEquals(2, Review::where('user_id', $user->id)->count());
    }
}
