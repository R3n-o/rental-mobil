<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get completed bookings to add reviews
        $completedBookings = Booking::where('status', 'completed')->get();

        $reviewComments = [
            5 => [
                'Mobil sangat nyaman dan bersih. Pelayanan sangat memuaskan!',
                'Excellent! Kondisi mobil prima dan sangat terawat.',
                'Sangat puas dengan layanannya. Pasti akan rental lagi.',
                'Mobil sesuai dengan deskripsi, AC dingin dan bersih.',
                'Pengalaman rental yang luar biasa. Highly recommended!',
            ],
            4 => [
                'Mobil bagus dan nyaman, hanya ada sedikit goresan kecil.',
                'Pelayanan baik, mobil dalam kondisi yang memuaskan.',
                'Overall bagus, cuma proses pengambilan agak lama.',
                'Mobil oke, bersih dan terawat. Harga cukup reasonable.',
            ],
            3 => [
                'Mobil cukup baik, tapi AC kurang dingin.',
                'Standar saja, tidak ada yang istimewa.',
                'Lumayan untuk harga segitu.',
            ],
            2 => [
                'Kondisi mobil kurang terawat, ada beberapa masalah kecil.',
                'Pelayanan kurang responsif.',
            ],
            1 => [
                'Sangat kecewa, mobil tidak sesuai ekspektasi.',
            ],
        ];

        foreach ($completedBookings as $booking) {
            $rating = fake()->randomElement([4, 4, 5, 5, 5, 3, 4, 5]);
            $comments = $reviewComments[$rating];

            Review::create([
                'user_id' => $booking->user_id,
                'car_id' => $booking->car_id,
                'rating' => $rating,
                'comment' => fake()->randomElement($comments),
            ]);
        }

        // Add some additional random reviews
        Review::factory(5)->create();
    }
}
