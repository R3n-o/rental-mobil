<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = [
            // SUV
            [
                'category' => 'SUV',
                'name' => 'Fortuner',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 1234 ABC',
                'daily_rent_price' => 850000,
                'is_available' => true,
            ],
            [
                'category' => 'SUV',
                'name' => 'Pajero Sport',
                'brand' => 'Mitsubishi',
                'model' => '2023',
                'plate_number' => 'B 5678 DEF',
                'daily_rent_price' => 900000,
                'is_available' => true,
            ],
            [
                'category' => 'SUV',
                'name' => 'CR-V',
                'brand' => 'Honda',
                'model' => '2024',
                'plate_number' => 'B 9012 GHI',
                'daily_rent_price' => 750000,
                'is_available' => true,
            ],
            // MPV
            [
                'category' => 'MPV',
                'name' => 'Avanza',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 3456 JKL',
                'daily_rent_price' => 350000,
                'is_available' => true,
            ],
            [
                'category' => 'MPV',
                'name' => 'Xenia',
                'brand' => 'Daihatsu',
                'model' => '2023',
                'plate_number' => 'B 7890 MNO',
                'daily_rent_price' => 300000,
                'is_available' => true,
            ],
            [
                'category' => 'MPV',
                'name' => 'Innova Zenix',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 1122 PQR',
                'daily_rent_price' => 650000,
                'is_available' => true,
            ],
            [
                'category' => 'MPV',
                'name' => 'Xpander',
                'brand' => 'Mitsubishi',
                'model' => '2024',
                'plate_number' => 'B 3344 STU',
                'daily_rent_price' => 400000,
                'is_available' => false,
            ],
            // Sedan
            [
                'category' => 'Sedan',
                'name' => 'Civic',
                'brand' => 'Honda',
                'model' => '2024',
                'plate_number' => 'B 5566 VWX',
                'daily_rent_price' => 550000,
                'is_available' => true,
            ],
            [
                'category' => 'Sedan',
                'name' => 'Camry',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 7788 YZA',
                'daily_rent_price' => 700000,
                'is_available' => true,
            ],
            // Hatchback
            [
                'category' => 'Hatchback',
                'name' => 'Jazz',
                'brand' => 'Honda',
                'model' => '2023',
                'plate_number' => 'B 9900 BCD',
                'daily_rent_price' => 300000,
                'is_available' => true,
            ],
            [
                'category' => 'Hatchback',
                'name' => 'Yaris',
                'brand' => 'Toyota',
                'model' => '2023',
                'plate_number' => 'B 1133 EFG',
                'daily_rent_price' => 320000,
                'is_available' => true,
            ],
            // City Car
            [
                'category' => 'City Car',
                'name' => 'Brio',
                'brand' => 'Honda',
                'model' => '2024',
                'plate_number' => 'B 2244 HIJ',
                'daily_rent_price' => 250000,
                'is_available' => true,
            ],
            [
                'category' => 'City Car',
                'name' => 'Agya',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 3355 KLM',
                'daily_rent_price' => 220000,
                'is_available' => true,
            ],
            // Pickup
            [
                'category' => 'Pickup',
                'name' => 'Hilux',
                'brand' => 'Toyota',
                'model' => '2023',
                'plate_number' => 'B 4466 NOP',
                'daily_rent_price' => 500000,
                'is_available' => true,
            ],
            [
                'category' => 'Pickup',
                'name' => 'Triton',
                'brand' => 'Mitsubishi',
                'model' => '2023',
                'plate_number' => 'B 5577 QRS',
                'daily_rent_price' => 480000,
                'is_available' => false,
            ],
            // Sport
            [
                'category' => 'Sport',
                'name' => '86',
                'brand' => 'Toyota',
                'model' => '2023',
                'plate_number' => 'B 6688 TUV',
                'daily_rent_price' => 1200000,
                'is_available' => true,
            ],
            // Luxury
            [
                'category' => 'Luxury',
                'name' => 'Alphard',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 7799 WXY',
                'daily_rent_price' => 2500000,
                'is_available' => true,
            ],
            [
                'category' => 'Luxury',
                'name' => 'Vellfire',
                'brand' => 'Toyota',
                'model' => '2024',
                'plate_number' => 'B 8800 ZAB',
                'daily_rent_price' => 2300000,
                'is_available' => true,
            ],
        ];

        foreach ($cars as $carData) {
            $category = Category::where('name', $carData['category'])->first();

            Car::create([
                'category_id' => $category->id,
                'name' => $carData['name'],
                'brand' => $carData['brand'],
                'model' => $carData['model'],
                'plate_number' => $carData['plate_number'],
                'daily_rent_price' => $carData['daily_rent_price'],
                'is_available' => $carData['is_available'],
                'image' => null,
            ]);
        }
    }
}
