<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_can_be_created(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create();

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'start_date' => '2026-01-15',
            'end_date' => '2026-01-17',
            'total_price' => 1050000,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'car_id' => $car->id,
            'status' => 'pending',
        ]);
    }

    public function test_booking_belongs_to_user(): void
    {
        $user = User::factory()->create(['name' => 'Test User']);
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $booking->user);
        $this->assertEquals('Test User', $booking->user->name);
    }

    public function test_booking_belongs_to_car(): void
    {
        $car = Car::factory()->create(['name' => 'Avanza']);
        $booking = Booking::factory()->create(['car_id' => $car->id]);

        $this->assertInstanceOf(Car::class, $booking->car);
        $this->assertEquals('Avanza', $booking->car->name);
    }

    public function test_booking_has_payment_relationship(): void
    {
        $booking = Booking::factory()->create();

        $this->assertNull($booking->payment);

        Payment::factory()->create(['booking_id' => $booking->id]);

        $booking->refresh();

        $this->assertInstanceOf(Payment::class, $booking->payment);
    }

    public function test_booking_has_pending_status_by_default(): void
    {
        $booking = Booking::factory()->create();

        $this->assertEquals('pending', $booking->status);
    }

    public function test_booking_can_be_confirmed(): void
    {
        $booking = Booking::factory()->confirmed()->create();

        $this->assertEquals('confirmed', $booking->status);
    }

    public function test_booking_can_be_ongoing(): void
    {
        $booking = Booking::factory()->ongoing()->create();

        $this->assertEquals('ongoing', $booking->status);
    }

    public function test_booking_can_be_completed(): void
    {
        $booking = Booking::factory()->completed()->create();

        $this->assertEquals('completed', $booking->status);
    }

    public function test_booking_fillable_attributes(): void
    {
        $booking = new Booking();
        $expected = [
            'user_id',
            'car_id',
            'start_date',
            'end_date',
            'total_price',
            'status'
        ];

        $this->assertEquals($expected, $booking->getFillable());
    }

    public function test_booking_total_price_calculation(): void
    {
        $car = Car::factory()->create(['daily_rent_price' => 300000]);

        // 3 days rental
        $booking = Booking::factory()->create([
            'car_id' => $car->id,
            'start_date' => '2026-01-15',
            'end_date' => '2026-01-17',
            'total_price' => 900000, // 3 days * 300000
        ]);

        $this->assertEquals(900000, $booking->total_price);
    }
}
