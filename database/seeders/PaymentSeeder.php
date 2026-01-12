<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get bookings that need payments
        $completedBookings = Booking::where('status', 'completed')->get();
        $ongoingBookings = Booking::where('status', 'ongoing')->get();
        $confirmedBookings = Booking::where('status', 'confirmed')->get();
        $pendingBookings = Booking::where('status', 'pending')->get();

        // Payments for completed bookings (verified payments)
        foreach ($completedBookings as $booking) {
            Payment::create([
                'booking_id' => $booking->id,
                'payment_date' => Carbon::parse($booking->start_date)->subDays(1)->format('Y-m-d'),
                'amount' => $booking->total_price,
                'payment_method' => fake()->randomElement(['transfer', 'e-wallet']),
                'proof_image' => null,
                'status' => 'verified',
            ]);
        }

        // Payments for ongoing bookings (verified payments)
        foreach ($ongoingBookings as $booking) {
            Payment::create([
                'booking_id' => $booking->id,
                'payment_date' => Carbon::parse($booking->start_date)->subDays(1)->format('Y-m-d'),
                'amount' => $booking->total_price,
                'payment_method' => fake()->randomElement(['transfer', 'e-wallet']),
                'proof_image' => null,
                'status' => 'verified',
            ]);
        }

        // Payments for confirmed bookings (verified payments)
        foreach ($confirmedBookings as $booking) {
            Payment::create([
                'booking_id' => $booking->id,
                'payment_date' => Carbon::now()->format('Y-m-d'),
                'amount' => $booking->total_price,
                'payment_method' => fake()->randomElement(['transfer', 'e-wallet']),
                'proof_image' => null,
                'status' => 'verified',
            ]);
        }

        // Payments for pending bookings (pending payments)
        foreach ($pendingBookings as $booking) {
            Payment::create([
                'booking_id' => $booking->id,
                'payment_date' => Carbon::now()->format('Y-m-d'),
                'amount' => $booking->total_price,
                'payment_method' => 'transfer',
                'proof_image' => null,
                'status' => 'pending',
            ]);
        }
    }
}
