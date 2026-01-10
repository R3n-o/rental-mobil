<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action'); // INSERT, UPDATE, DELETE, LOGIN
            $table->string('url'); // URL yang diakses
            $table->string('method'); // POST, PUT, DELETE
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable(); // Info Browser/Device
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};