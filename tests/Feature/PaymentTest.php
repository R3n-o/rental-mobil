<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_payment(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $token = auth()->guard('api')->login($user);
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'total_price' => 1000000,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/payments', [
                'booking_id' => $booking->id,
                'amount' => 1000000,
                'payment_method' => 'transfer',
                'proof_image' => UploadedFile::fake()->image('bukti.jpg'),
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'amount' => 1000000,
            'payment_method' => 'transfer',
        ]);
    }

    public function test_admin_can_update_payment_status(): void
    {
        $admin = User::factory()->admin()->create();
        $token = auth()->guard('api')->login($admin);

        $booking = Booking::factory()->create();
        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'status' => 'pending',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson("/api/payments/{$payment->id}", [
                'status' => 'confirmed',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_unauthenticated_user_cannot_create_payment(): void
    {
        $booking = Booking::factory()->create();

        $response = $this->postJson('/api/payments', [
            'booking_id' => $booking->id,
            'amount' => 1000000,
            'payment_method' => 'transfer',
        ]);

        $response->assertStatus(401);
    }
}
