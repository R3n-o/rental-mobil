<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            // Relasi ke categories
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Contoh: Avanza Veloz
            $table->string('brand'); // Toyota
            $table->string('model'); // 2023
            $table->string('plate_number')->unique();
            $table->decimal('daily_rent_price', 10, 2);
            $table->boolean('is_available')->default(true);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};