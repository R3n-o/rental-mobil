<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_user_has_default_customer_role(): void
    {
        $user = User::factory()->create();

        $this->assertEquals('customer', $user->role);
    }

    public function test_user_can_be_admin(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertEquals('admin', $user->role);
    }

    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => 'password123',
        ]);

        $this->assertNotEquals('password123', $user->password);
    }

    public function test_user_has_bookings_relationship(): void
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $user->bookings);
    }

    public function test_user_jwt_identifier(): void
    {
        $user = User::factory()->create();

        $this->assertEquals($user->id, $user->getJWTIdentifier());
    }

    public function test_user_jwt_custom_claims_contains_role(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $claims = $user->getJWTCustomClaims();

        $this->assertArrayHasKey('role', $claims);
        $this->assertEquals('admin', $claims['role']);
    }
}
