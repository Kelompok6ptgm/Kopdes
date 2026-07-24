<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles
        DB::table('role')->insert([
            ['id_role' => 1, 'nama_role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id_role' => 2, 'nama_role' => 'manager', 'created_at' => now(), 'updated_at' => now()],
            ['id_role' => 3, 'nama_role' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed default users for testing
        User::create([
            'id_role' => 1,
            'nama' => 'Administrator Kopdes',
            'email' => 'admin@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567890',
            'alamat' => 'Kantor Koperasi Desa, Jl. Merdeka No. 1',
            'status' => 'aktif',
        ]);

        User::create([
            'id_role' => 2,
            'nama' => 'Manager Kopdes',
            'email' => 'manager@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567891',
            'alamat' => 'Kantor Koperasi Desa, Jl. Merdeka No. 2',
            'status' => 'aktif',
        ]);

        User::create([
            'id_role' => 3,
            'nama' => 'User Kopdes',
            'email' => 'user@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567892',
            'alamat' => 'Dusun Sukamaju, RT 01 RW 02',
            'status' => 'aktif',
        ]);
    }
}
