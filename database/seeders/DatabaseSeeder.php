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
        // 1. Seed roles
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
            'kode_pos' => '12190',
            'alamat' => 'Kantor Pusat Platform, Jakarta',
        ]);

        $manager = User::create([
            'id_role' => 2,
            'id_kopdes' => $kopdesJakarta->id_kopdes,
            'nama' => 'Manager KopDes Jakarta',
            'email' => 'manager@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567891',
            'kode_pos' => '12190',
            'alamat' => 'Jl. Jend. Sudirman No. 88, Jakarta Selatan',
        ]);

        $user = User::create([
            'id_role' => 3,
            'nama' => 'User Kopdes',
            'email' => 'user@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567892',
            'kode_pos' => '12190',
            'alamat' => 'Kebayoran Baru, Jakarta Selatan',
        ]);

        // 4. Seed Categories for KopDes Jakarta
        $catSembako = Category::create([
            'id_kopdes' => $kopdesJakarta->id_kopdes,
            'nama_kategori' => 'Sembako & Kebutuhan Pokok',
        ]);

        $catHasilTani = Category::create([
            'id_kopdes' => $kopdesJakarta->id_kopdes,
            'nama_kategori' => 'Hasil Tani & Olahan Lokal',
        ]);

        // 5. Seed Products for KopDes Jakarta
        Product::create([
            'id_kopdes' => $kopdesJakarta->id_kopdes,
            'id_category' => $catSembako->id_category,
            'nama_produk' => 'Beras Premium Ramos 5kg',
            'deskripsi' => 'Beras putih pulen kualitas beras lokal Indonesia.',
            'harga' => 78000.00,
            'stok' => 50,
        ]);

        Product::create([
            'id_kopdes' => $kopdesJakarta->id_kopdes,
            'id_category' => $catSembako->id_category,
            'nama_produk' => 'Minyak Goreng Sawit 2 Liter',
            'deskripsi' => 'Minyak goreng kelapa sawit jernih dan higienis.',
            'harga' => 34000.00,
            'stok' => 100,
        ]);

        Product::create([
            'id_kopdes' => $kopdesJakarta->id_kopdes,
            'id_category' => $catSembako->id_category,
            'nama_produk' => 'Gula Pasir Tebu 1kg',
            'deskripsi' => 'Gula pasir kristal murni manis alami.',
            'harga' => 17500.00,
            'stok' => 80,
        ]);

        Product::create([
            'id_kopdes' => $kopdesJakarta->id_kopdes,
            'id_category' => $catHasilTani->id_category,
            'nama_produk' => 'Kopi Robusta Asli 250g',
            'deskripsi' => 'Kopi bubuk murni aroma khas Nusantara.',
            'harga' => 45000.00,
            'stok' => 35,
        ]);

        Product::create([
            'id_kopdes' => $kopdesJakarta->id_kopdes,
            'id_category' => $catHasilTani->id_category,
            'nama_produk' => 'Madu Alami Murni 500ml',
            'deskripsi' => 'Madu lebah liar kaya nutrisi dan stamina.',
            'harga' => 95000.00,
            'stok' => 20,
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
