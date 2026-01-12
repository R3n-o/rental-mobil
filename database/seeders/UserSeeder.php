<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@rental.com',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta',
            'sim_number' => '1234567890',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Customer users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891',
            'address' => 'Jl. Pelanggan No. 1, Bandung',
            'sim_number' => '1234567891',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'address' => 'Jl. Pelanggan No. 2, Surabaya',
            'sim_number' => '1234567892',
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Generate more random customers
        User::factory(7)->create();
    }
}
