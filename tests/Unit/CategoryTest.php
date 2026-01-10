<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created(): void
    {
        $category = Category::factory()->create([
            'name' => 'SUV',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'SUV',
        ]);
    }

    public function test_category_has_cars_relationship(): void
    {
        $category = Category::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $category->cars);
    }

    public function test_category_can_have_multiple_cars(): void
    {
        $category = Category::factory()->create();

        Car::factory()->count(3)->create([
            'category_id' => $category->id,
        ]);

        $this->assertCount(3, $category->cars);
    }

    public function test_category_fillable_attributes(): void
    {
        $category = new Category();

        $this->assertEquals(['name'], $category->getFillable());
    }
}
