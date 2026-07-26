<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kopdes;
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
        $admin = User::create([
            'id_role' => 1,
            'nama' => 'Administrator Kopdes',
            'email' => 'admin@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567890',
            'alamat' => 'Kantor Koperasi Desa, Jl. Merdeka No. 1',
            'status' => 'aktif',
        ]);

        $manager = User::create([
            'id_role' => 2,
            'nama' => 'Manager Kopdes',
            'email' => 'manager@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567891',
            'alamat' => 'Kantor Koperasi Desa, Jl. Merdeka No. 2',
            'status' => 'aktif',
        ]);

        $user = User::create([
            'id_role' => 3,
            'nama' => 'User Kopdes',
            'email' => 'user@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567892',
            'alamat' => 'Dusun Sukamaju, RT 01 RW 02',
            'status' => 'aktif',
        ]);

        // Seed Kopdes
        Kopdes::create([
            'nama_kopdes' => 'KopDes Jakarta',
            'email' => 'jakarta@kopdes.com',
            'no_telp' => '021-123456',
            'alamat' => 'Jl. Sudirman No. 10, Jakarta Pusat',
            'status' => 'aktif',
        ]);

        Kopdes::create([
            'nama_kopdes' => 'KopDes Bogor',
            'email' => 'bogor@kopdes.com',
            'no_telp' => '0251-654321',
            'alamat' => 'Jl. Pajajaran No. 22, Bogor',
            'status' => 'aktif',
        ]);

        Kopdes::create([
            'nama_kopdes' => 'KopDes Depok',
            'email' => 'depok@kopdes.com',
            'no_telp' => '021-987654',
            'alamat' => 'Jl. Margonda Raya No. 5, Depok',
            'status' => 'aktif',
        ]);

        Kopdes::create([
            'nama_kopdes' => 'KopDes Tangerang',
            'email' => 'tangerang@kopdes.com',
            'no_telp' => '021-456789',
            'alamat' => 'Jl. Serpong No. 12, Tangerang',
            'status' => 'aktif',
        ]);

        Kopdes::create([
            'nama_kopdes' => 'KopDes Bekasi',
            'email' => 'bekasi@kopdes.com',
            'no_telp' => '021-321654',
            'alamat' => 'Jl. A. Yani No. 8, Bekasi',
            'status' => 'nonaktif',
        ]);
    }
}
