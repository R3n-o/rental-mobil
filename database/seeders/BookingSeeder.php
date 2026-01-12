<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $cars = Car::all();

        // Completed booking (masa lalu)
        $booking1 = Booking::create([
            'user_id' => $customers[0]->id,
            'car_id' => $cars[0]->id,
            'start_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'end_date' => Carbon::now()->subDays(7)->format('Y-m-d'),
            'total_price' => $cars[0]->daily_rent_price * 3,
            'status' => 'completed',
        ]);

        // Completed booking (masa lalu)
        $booking2 = Booking::create([
            'user_id' => $customers[1]->id,
            'car_id' => $cars[1]->id,
            'start_date' => Carbon::now()->subDays(14)->format('Y-m-d'),
            'end_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
            'total_price' => $cars[1]->daily_rent_price * 4,
            'status' => 'completed',
        ]);

        // Ongoing booking (sedang berlangsung)
        $booking3 = Booking::create([
            'user_id' => $customers[0]->id,
            'car_id' => $cars[3]->id,
            'start_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'total_price' => $cars[3]->daily_rent_price * 5,
            'status' => 'ongoing',
        ]);

        // Confirmed booking (akan datang)
        $booking4 = Booking::create([
            'user_id' => $customers[1]->id,
            'car_id' => $cars[4]->id,
            'start_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(8)->format('Y-m-d'),
            'total_price' => $cars[4]->daily_rent_price * 3,
            'status' => 'confirmed',
        ]);

        // Pending booking
        $booking5 = Booking::create([
            'user_id' => $customers[0]->id,
            'car_id' => $cars[7]->id,
            'start_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(12)->format('Y-m-d'),
            'total_price' => $cars[7]->daily_rent_price * 2,
            'status' => 'pending',
        ]);

        // Cancelled booking
        $booking6 = Booking::create([
            'user_id' => $customers[1]->id,
            'car_id' => $cars[8]->id,
            'start_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'total_price' => $cars[8]->daily_rent_price * 2,
            'status' => 'cancelled',
        ]);

        // Generate more random bookings using factory
        Booking::factory(5)->create();
    }
}
