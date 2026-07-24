<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles for each test
        \DB::table('role')->insert([
            ['id_role' => 1, 'nama_role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id_role' => 2, 'nama_role' => 'manager', 'created_at' => now(), 'updated_at' => now()],
            ['id_role' => 3, 'nama_role' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_guest_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Selamat Datang Kembali!');
    }

    public function test_guest_can_view_register_page()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran Anggota');
    }

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Mawar No. 12',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        
        $this->assertDatabaseHas('user', [
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'id_role' => 3,
        ]);
    }

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::create([
            'id_role' => 3,
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Mawar No. 12',
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'email' => 'budi@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        $user = User::create([
            'id_role' => 3,
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Mawar No. 12',
            'status' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'email' => 'budi@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_logged_in_user_can_logout()
    {
        $user = User::create([
            'id_role' => 3,
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '08123456789',
            'alamat' => 'Jl. Mawar No. 12',
            'status' => 'aktif',
        ]);

        $response = $this->actingAs($user)->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
