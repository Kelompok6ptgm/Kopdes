<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Kopdes;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseDesignTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles
        \DB::table('role')->insert([
            ['id_role' => 1, 'nama_role' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id_role' => 2, 'nama_role' => 'manager', 'created_at' => now(), 'updated_at' => now()],
            ['id_role' => 3, 'nama_role' => 'user', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_entire_database_design_and_eloquent_relationships()
    {
        // 1. Create a Kopdes
        $kopdes = Kopdes::create([
            'nama_kopdes' => 'Kopdes Sukamaju',
            'alamat' => 'Dusun Sukamaju No. 12',
            'no_hp' => '081234567890',
            'status' => 'aktif',
        ]);
        $this->assertDatabaseHas('kopdes', ['nama_kopdes' => 'Kopdes Sukamaju']);

        // 2. Create a Manager for the Kopdes
        $manager = User::create([
            'id_role' => 2,
            'id_kopdes' => $kopdes->id_kopdes,
            'nama' => 'Manager Budi',
            'email' => 'budi@kopdes.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567891',
            'alamat' => 'Kantor Koperasi',
        ]);
        $this->assertEquals($kopdes->id_kopdes, $manager->kopdes->id_kopdes);
        $this->assertCount(1, $kopdes->users);

        // 3. Create a Category
        $category = Category::create([
            'id_kopdes' => $kopdes->id_kopdes,
            'nama_kategori' => 'Sembako',
        ]);
        $this->assertEquals($kopdes->id_kopdes, $category->kopdes->id_kopdes);
        $this->assertCount(1, $kopdes->categories);

        // 4. Create a Product
        $product = Product::create([
            'id_kopdes' => $kopdes->id_kopdes,
            'id_category' => $category->id_category,
            'nama_produk' => 'Beras Pandan Wangi 5kg',
            'deskripsi' => 'Beras kualitas super dari petani lokal.',
            'harga' => 75000.00,
            'stok' => 50,
        ]);
        $this->assertEquals($category->id_category, $product->category->id_category);
        $this->assertEquals($kopdes->id_kopdes, $product->kopdes->id_kopdes);
        $this->assertCount(1, $kopdes->products);
        $this->assertCount(1, $category->products);

        // 5. Create a User (Buyer)
        $buyer = User::create([
            'id_role' => 3,
            'nama' => 'Anggota Slamet',
            'email' => 'slamet@example.com',
            'password' => Hash::make('password'),
            'no_hp' => '081234567892',
            'alamat' => 'Dusun Sukamaju RT 03',
        ]);

        // 6. Add Product to Cart
        $cart = Cart::create([
            'id_user' => $buyer->id_user,
            'id_product' => $product->id_product,
            'quantity' => 2,
        ]);
        $this->assertEquals($buyer->id_user, $cart->user->id_user);
        $this->assertEquals($product->id_product, $cart->product->id_product);
        $this->assertCount(1, $buyer->carts);
        $this->assertCount(1, $product->carts);

        // 7. Checkout Cart to Transaction
        $transaction = Transaction::create([
            'id_user' => $buyer->id_user,
            'id_kopdes' => $kopdes->id_kopdes,
            'kode_transaksi' => 'TRX-20260725-001',
            'total_harga' => 150000.00,
            'status_transaksi' => 'menunggu_pembayaran',
            'alamat_pengiriman' => 'Dusun Sukamaju RT 03',
            'catatan' => 'Kirim sore hari.',
        ]);
        $this->assertEquals($buyer->id_user, $transaction->user->id_user);
        $this->assertEquals($kopdes->id_kopdes, $transaction->kopdes->id_kopdes);
        $this->assertCount(1, $buyer->transactions);
        $this->assertCount(1, $kopdes->transactions);

        // 8. Create Transaction Detail
        $detail = TransactionDetail::create([
            'id_transaction' => $transaction->id_transaction,
            'id_product' => $product->id_product,
            'quantity' => 2,
            'harga_beli' => 75000.00,
        ]);
        $this->assertEquals($transaction->id_transaction, $detail->transaction->id_transaction);
        $this->assertEquals($product->id_product, $detail->product->id_product);
        $this->assertCount(1, $transaction->details);
        $this->assertCount(1, $product->transactionDetails);

        // 9. Add Payment
        $payment = Payment::create([
            'id_transaction' => $transaction->id_transaction,
            'jumlah_bayar' => 150000.00,
            'metode_pembayaran' => 'transfer_bank',
            'bukti_pembayaran' => 'receipts/receipt-001.jpg',
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);
        $this->assertEquals($transaction->id_transaction, $payment->transaction->id_transaction);
        $this->assertEquals($payment->id_payment, $transaction->payment->id_payment);

        // 10. Verify Payment by Manager
        $payment->update([
            'status_pembayaran' => 'diterima',
            'diverifikasi_oleh' => $manager->id_user,
            'tanggal_verifikasi' => now(),
        ]);
        $this->assertEquals($manager->id_user, $payment->verifier->id_user);
        $this->assertCount(1, $manager->verifiedPayments);

        // 11. Create a Review
        $review = Review::create([
            'id_user' => $buyer->id_user,
            'id_product' => $product->id_product,
            'rating' => 5,
            'komentar' => 'Beras pulen dan enak sekali!',
        ]);
        $this->assertEquals($buyer->id_user, $review->user->id_user);
        $this->assertEquals($product->id_product, $review->product->id_product);
        $this->assertCount(1, $buyer->reviews);
        $this->assertCount(1, $product->reviews);
    }
}
