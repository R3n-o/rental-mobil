<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_own_bookings(): void
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);

        Booking::factory()->count(2)->create(['user_id' => $user->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/bookings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }

    public function test_admin_can_view_all_bookings(): void
    {
        $admin = User::factory()->admin()->create();
        $token = auth()->guard('api')->login($admin);

        Booking::factory()->count(5)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/bookings');

        $response->assertStatus(200);
        $this->assertCount(5, $response->json('data'));
    }

    public function test_user_can_create_booking(): void
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);
        $car = Car::factory()->create(['daily_rent_price' => 300000]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/bookings', [
                'car_id' => $car->id,
                'start_date' => '2026-01-20',
                'end_date' => '2026-01-22',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Booking berhasil dibuat',
            ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'car_id' => $car->id,
            'status' => 'pending',
        ]);
    }

    public function test_booking_calculates_total_price(): void
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);
        $car = Car::factory()->create(['daily_rent_price' => 300000]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/bookings', [
                'car_id' => $car->id,
                'start_date' => '2026-01-20',
                'end_date' => '2026-01-22', // 3 days
            ]);

        $response->assertStatus(201);

        $booking = Booking::where('user_id', $user->id)->first();
        $this->assertEquals(900000, $booking->total_price); // 3 days * 300000
    }

    public function test_cannot_book_already_booked_car(): void
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);
        $car = Car::factory()->create();

        // Create existing booking
        Booking::factory()->create([
            'car_id' => $car->id,
            'start_date' => '2026-01-20',
            'end_date' => '2026-01-25',
            'status' => 'confirmed',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/bookings', [
                'car_id' => $car->id,
                'start_date' => '2026-01-22',
                'end_date' => '2026-01-24',
            ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Mobil tidak tersedia pada tanggal tersebut.',
            ]);
    }

    public function test_user_can_view_own_booking_detail(): void
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/bookings/{$booking->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_user_cannot_view_other_users_booking(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $token = auth()->guard('api')->login($user1);

        $booking = Booking::factory()->create(['user_id' => $user2->id]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/bookings/{$booking->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_cancel_pending_booking(): void
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/bookings/{$booking->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan dan dihapus.',
            ]);

        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }

    public function test_user_cannot_cancel_confirmed_booking(): void
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);
        $booking = Booking::factory()->confirmed()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson("/api/bookings/{$booking->id}");

        $response->assertStatus(400);
    }

    public function test_unauthenticated_user_cannot_create_booking(): void
    {
        $car = Car::factory()->create();

        $response = $this->postJson('/api/bookings', [
            'car_id' => $car->id,
            'start_date' => '2026-01-20',
            'end_date' => '2026-01-22',
        ]);

        $response->assertStatus(401);
    }

    public function test_cannot_book_with_past_start_date(): void
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);
        $car = Car::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/bookings', [
                'car_id' => $car->id,
                'start_date' => '2025-01-01', // Past date
                'end_date' => '2025-01-05',
            ]);

        $response->assertStatus(422);
    }

    public function test_cannot_book_with_end_date_before_start_date(): void
    {
        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);
        $car = Car::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/bookings', [
                'car_id' => $car->id,
                'start_date' => '2026-01-25',
                'end_date' => '2026-01-20',
            ]);

        $response->assertStatus(422);
    }
}
