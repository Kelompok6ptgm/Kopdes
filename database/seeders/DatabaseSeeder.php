<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kopdes;
use App\Models\Category;
use App\Models\Product;
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

        // 2. Seed 1 KopDes daerah Jakarta (id_kopdes = 1)
        $kopdesJakarta = Kopdes::create([
            'nama_kopdes' => 'KopDes Merah Putih Jakarta',
            'alamat' => 'Jl. Jend. Sudirman No. 88, Jakarta Selatan',
            'kode_pos' => '12190',
            'provinsi' => 'DKI Jakarta',
            'no_hp' => '081298765432',
            'status' => 'aktif',
        ]);

        // 3. Seed default users
        User::create([
            'id_role' => 1,
            'nama' => 'Administrator Kopdes',
            'email' => 'admin@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567890',
            'kode_pos' => '12190',
            'alamat' => 'Kantor Pusat Platform, Jakarta',
        ]);

        // Manager ditugaskan mengelola KopDes Jakarta (id_kopdes = 1)
        User::create([
            'id_role' => 2,
            'id_kopdes' => $kopdesJakarta->id_kopdes,
            'nama' => 'Manager KopDes Jakarta',
            'email' => 'manager@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567891',
            'kode_pos' => '12190',
            'alamat' => 'Jl. Jend. Sudirman No. 88, Jakarta Selatan',
        ]);

        User::create([
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
    }
}
