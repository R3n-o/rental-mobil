<?php

namespace Tests\Unit;

use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_can_be_created(): void
    {
        $booking = Booking::factory()->create();

        $payment = Payment::factory()->create([
            'booking_id' => $booking->id,
            'payment_date' => '2026-01-10',
            'amount' => 1000000,
            'payment_method' => 'transfer',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'amount' => 1000000,
            'payment_method' => 'transfer',
        ]);
    }

    public function test_payment_belongs_to_booking(): void
    {
        $booking = Booking::factory()->create(['status' => 'pending']);
        $payment = Payment::factory()->create(['booking_id' => $booking->id]);

        $this->assertInstanceOf(Booking::class, $payment->booking);
        $this->assertEquals($booking->id, $payment->booking->id);
    }

    public function test_payment_has_pending_status_by_default(): void
    {
        $payment = Payment::factory()->create();

        $this->assertEquals('pending', $payment->status);
    }

    public function test_payment_can_be_confirmed(): void
    {
        $payment = Payment::factory()->confirmed()->create();

        $this->assertEquals('confirmed', $payment->status);
    }

    public function test_payment_can_be_rejected(): void
    {
        $payment = Payment::factory()->rejected()->create();

        $this->assertEquals('rejected', $payment->status);
    }

    public function test_payment_fillable_attributes(): void
    {
        $payment = new Payment();
        $expected = [
            'booking_id',
            'payment_date',
            'amount',
            'payment_method',
            'proof_image',
            'status'
        ];

        $this->assertEquals($expected, $payment->getFillable());
    }

    public function test_payment_methods(): void
    {
        $paymentTransfer = Payment::factory()->create(['payment_method' => 'transfer']);
        $paymentCash = Payment::factory()->create(['payment_method' => 'cash']);
        $paymentCard = Payment::factory()->create(['payment_method' => 'credit_card']);

        $this->assertEquals('transfer', $paymentTransfer->payment_method);
        $this->assertEquals('cash', $paymentCash->payment_method);
        $this->assertEquals('credit_card', $paymentCard->payment_method);
    }
}
