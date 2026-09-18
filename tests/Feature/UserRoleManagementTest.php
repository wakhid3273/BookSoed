<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_defaults_to_user_role(): void
    {
        $response = $this->post('/register', [
            'name' => 'Mahasiswa Test',
            'email' => 'mahasiswa@unsoed.ac.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');

        $user = User::where('email', 'mahasiswa@unsoed.ac.id')->first();
        $this->assertNotNull($user);
        $this->assertEquals('user', $user->role);
        $this->assertNotEquals('password123', $user->password);
    }

    public function test_regular_user_cannot_access_admin_area(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_admin_area(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('ERP Admin Area');
    }

    public function test_user_cannot_update_their_own_role_via_profile_update(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Name Updated',
            'email' => $user->email,
            'role' => 'admin',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertEquals('user', $user->fresh()->role);
    }

    public function test_user_relationships_to_book_and_order_remain_intact(): void
    {
        $user = User::factory()->create();
        $this->assertTrue(method_exists($user, 'books'));
        $this->assertTrue(method_exists($user, 'buyerOrders'));
        $this->assertTrue(method_exists($user, 'sellerOrders'));
    }
}
