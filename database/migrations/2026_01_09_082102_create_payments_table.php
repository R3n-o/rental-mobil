<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            // Relasi ke Booking
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method'); // Transfer Bank, E-Wallet
            $table->string('proof_image')->nullable(); // Bukti transfer
            $table->enum('status', ['pending', 'verified', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};