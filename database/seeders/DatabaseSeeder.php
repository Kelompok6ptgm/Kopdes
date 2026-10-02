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

        // 2. Seed Kopdes first
        $kopdesJakarta = Kopdes::create([
            'nama_kopdes' => 'KopDes Jakarta',
            'no_hp' => '021123456',
            'alamat' => 'Jl. Sudirman No. 10, Jakarta Pusat',
            'kode_pos' => '12190',
            'provinsi' => 'DKI Jakarta',
            'status' => 'aktif',
        ]);

        $kopdesBogor = Kopdes::create([
            'nama_kopdes' => 'KopDes Bogor',
            'no_hp' => '0251654321',
            'alamat' => 'Jl. Pajajaran No. 22, Bogor',
            'kode_pos' => '16123',
            'provinsi' => 'Jawa Barat',
            'status' => 'aktif',
        ]);

        $kopdesDepok = Kopdes::create([
            'nama_kopdes' => 'KopDes Depok',
            'no_hp' => '021987654',
            'alamat' => 'Jl. Margonda Raya No. 5, Depok',
            'kode_pos' => '16424',
            'provinsi' => 'Jawa Barat',
            'status' => 'aktif',
        ]);

        $kopdesTangerang = Kopdes::create([
            'nama_kopdes' => 'KopDes Tangerang',
            'no_hp' => '021456789',
            'alamat' => 'Jl. Serpong No. 12, Tangerang',
            'kode_pos' => '15310',
            'provinsi' => 'Banten',
            'status' => 'aktif',
        ]);

        $kopdesBekasi = Kopdes::create([
            'nama_kopdes' => 'KopDes Bekasi',
            'no_hp' => '021321654',
            'alamat' => 'Jl. A. Yani No. 8, Bekasi',
            'kode_pos' => '17144',
            'provinsi' => 'Jawa Barat',
            'status' => 'nonaktif',
        ]);

        // 3. Seed users
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
    }
}
