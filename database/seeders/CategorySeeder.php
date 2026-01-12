<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'SUV'],
            ['name' => 'Sedan'],
            ['name' => 'MPV'],
            ['name' => 'Hatchback'],
            ['name' => 'Pickup'],
            ['name' => 'Sport'],
            ['name' => 'City Car'],
            ['name' => 'Luxury'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
