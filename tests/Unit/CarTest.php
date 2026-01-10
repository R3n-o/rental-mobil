<?php

namespace Tests\Unit;

use App\Models\Car;
use App\Models\Category;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    public function test_car_can_be_created(): void
    {
        $category = Category::factory()->create();

        $car = Car::factory()->create([
            'category_id' => $category->id,
            'name' => 'Avanza',
            'brand' => 'Toyota',
            'model' => '2024',
            'plate_number' => 'B 1234 ABC',
            'daily_rent_price' => 350000,
        ]);

        $this->assertDatabaseHas('cars', [
            'name' => 'Avanza',
            'brand' => 'Toyota',
            'plate_number' => 'B 1234 ABC',
        ]);
    }

    public function test_car_belongs_to_category(): void
    {
        $category = Category::factory()->create(['name' => 'SUV']);
        $car = Car::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $car->category);
        $this->assertEquals('SUV', $car->category->name);
    }

    public function test_car_has_bookings_relationship(): void
    {
        $car = Car::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $car->bookings);
    }

    public function test_car_has_reviews_relationship(): void
    {
        $car = Car::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $car->reviews);
    }

    public function test_car_is_available_by_default(): void
    {
        $car = Car::factory()->create();

        $this->assertTrue((bool) $car->is_available);
    }

    public function test_car_can_be_unavailable(): void
    {
        $car = Car::factory()->unavailable()->create();

        $this->assertFalse((bool) $car->is_available);
    }

    public function test_car_fillable_attributes(): void
    {
        $car = new Car();
        $expected = [
            'category_id',
            'name',
            'brand',
            'model',
            'plate_number',
            'daily_rent_price',
            'is_available',
            'image'
        ];

        $this->assertEquals($expected, $car->getFillable());
    }

    public function test_car_plate_number_is_unique(): void
    {
        Car::factory()->create(['plate_number' => 'B 1234 ABC']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Car::factory()->create(['plate_number' => 'B 1234 ABC']);
    }
}
