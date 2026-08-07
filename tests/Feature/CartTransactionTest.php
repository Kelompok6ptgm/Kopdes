<?php

namespace Tests\Feature;

use App\Models\Kopdes;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CartTransactionTest extends TestCase
{
    use RefreshDatabase;

    private User $member;
    private User $manager;
    private Kopdes $kopdes;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        // ponytail: setup roles
        $roleAdmin = Role::create(['nama_role' => 'admin']);
        $roleManager = Role::create(['nama_role' => 'manager']);
        $roleMember = Role::create(['nama_role' => 'user']);

        $this->kopdes = Kopdes::create([
            'nama_kopdes' => 'Kopdes Sukamaju',
            'alamat' => 'Sukamaju',
            'kode_pos' => '12345',
            'status' => 'aktif',
            'no_hp' => '081234567890',
        ]);

        $this->member = User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
            'kode_pos' => '12345',
            'id_role' => $roleMember->id_role,
            'id_kopdes' => $this->kopdes->id_kopdes,
            'no_hp' => '081234567891',
            'alamat' => 'Sukamaju',
        ]);

        $this->manager = User::create([
            'nama' => 'Pak Manager',
            'email' => 'manager@example.com',
            'password' => Hash::make('password123'),
            'kode_pos' => '12345',
            'id_role' => $roleManager->id_role,
            'id_kopdes' => $this->kopdes->id_kopdes,
            'no_hp' => '081234567892',
            'alamat' => 'Sukamaju',
        ]);

        $category = Category::create([
            'nama_kategori' => 'Sembako',
            'id_kopdes' => $this->kopdes->id_kopdes,
        ]);

        $this->product = Product::create([
            'nama_produk' => 'Beras Organik',
            'harga' => 15000,
            'stok' => 50,
            'deskripsi' => 'Beras kualitas super',
            'id_kopdes' => $this->kopdes->id_kopdes,
            'id_category' => $category->id_category,
        ]);
    }

    public function test_guest_can_add_item_to_session_cart(): void
    {
        $response = $this->postJson(route('cart.add'), [
            'id_product' => $this->product->id_product,
            'quantity' => 2,
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $this->assertEquals(2, session('cart.' . $this->product->id_product));
    }

    public function test_login_merges_session_cart_to_database(): void
    {
        // Add to guest session cart first
        session(['cart' => [$this->product->id_product => 3]]);

        $response = $this->post('/login', [
            'email' => $this->member->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('cart', [
            'id_user' => $this->member->id_user,
            'id_product' => $this->product->id_product,
            'quantity' => 3,
        ]);
    }

    public function test_checkout_reduces_stock_securely(): void
    {
        $this->actingAs($this->member);

        // Add to DB cart
        Cart::create([
            'id_user' => $this->member->id_user,
            'id_product' => $this->product->id_product,
            'quantity' => 5,
        ]);

        $response = $this->post(route('checkout'), [
            'alamat_pengiriman' => 'Jalan Kebenaran No. 1',
            'catatan' => 'Segera kirim ya',
        ]);

        $response->assertRedirect();
        
        // Assert stock reduced from 50 to 45
        $this->product->refresh();
        $this->assertEquals(45, $this->product->stok);

        // Assert transaction created
        $this->assertDatabaseHas('transaction', [
            'id_user' => $this->member->id_user,
            'id_kopdes' => $this->kopdes->id_kopdes,
            'total_harga' => 75000,
            'status_transaksi' => 'menunggu_pembayaran',
        ]);
    }

    public function test_manager_can_verify_payment(): void
    {
        Storage::fake('public');

        $trx = Transaction::create([
            'id_user' => $this->member->id_user,
            'id_kopdes' => $this->kopdes->id_kopdes,
            'kode_transaksi' => 'TRX-' . uniqid(),
            'total_harga' => 75000,
            'alamat_pengiriman' => 'Jalan Kebenaran No. 1',
            'status_transaksi' => 'menunggu_pembayaran',
        ]);

        $payment = Payment::create([
            'id_transaction' => $trx->id_transaction,
            'jumlah_bayar' => 75000,
            'metode_pembayaran' => 'Transfer Bank',
            'bukti_pembayaran' => 'payments/dummy.png',
            'status_pembayaran' => 'menunggu_verifikasi',
        ]);

        $this->actingAs($this->manager);

        $response = $this->post(route('manager.payments.verify', $payment->id_payment), [
            'action' => 'approve',
        ]);

        $response->assertRedirect();
        
        $payment->refresh();
        $trx->refresh();

        $this->assertEquals('diverifikasi', $payment->status_pembayaran);
        $this->assertEquals('diproses', $trx->status_transaksi);
    }

    public function test_cancel_transaction_restores_stock(): void
    {
        $trx = Transaction::create([
            'id_user' => $this->member->id_user,
            'id_kopdes' => $this->kopdes->id_kopdes,
            'kode_transaksi' => 'TRX-' . uniqid(),
            'total_harga' => 75000,
            'alamat_pengiriman' => 'Jalan Kebenaran No. 1',
            'status_transaksi' => 'menunggu_pembayaran',
        ]);

        // Detail transaksi (which deducted stock)
        $trx->details()->create([
            'id_product' => $this->product->id_product,
            'quantity' => 10,
            'harga_satuan' => 15000,
            'harga_beli' => 15000,
        ]);

        // Stock was 50, now we cancel the transaction. It should go back to 50 + 10 = 60.
        // Wait, normally we deduct stock on checkout. For testing cancellation restore, we start with $this->product->stok = 50.
        $this->actingAs($this->member);

        $response = $this->post(route('transaction.cancel', $trx->id_transaction));

        $response->assertRedirect();
        $trx->refresh();
        $this->product->refresh();

        $this->assertEquals('dibatalkan', $trx->status_transaksi);
        $this->assertEquals(60, $this->product->stok);
    }

    public function test_manager_can_update_transaction_status(): void
    {
        $trx = Transaction::create([
            'id_user' => $this->member->id_user,
            'id_kopdes' => $this->kopdes->id_kopdes,
            'kode_transaksi' => 'TRX-' . uniqid(),
            'total_harga' => 75000,
            'alamat_pengiriman' => 'Jalan Kebenaran No. 1',
            'status_transaksi' => 'diproses',
        ]);

        $this->actingAs($this->manager);

        $response = $this->post(route('manager.transactions.status', $trx->id_transaction), [
            'status' => 'dikirim',
        ]);

        $response->assertRedirect();
        $trx->refresh();

        $this->assertEquals('dikirim', $trx->status_transaksi);
    }

    public function test_user_can_submit_review_for_completed_transaction(): void
    {
        $trx = Transaction::create([
            'id_user' => $this->member->id_user,
            'id_kopdes' => $this->kopdes->id_kopdes,
            'kode_transaksi' => 'TRX-' . uniqid(),
            'total_harga' => 15000,
            'alamat_pengiriman' => 'Jalan Kebenaran No. 1',
            'status_transaksi' => 'selesai',
        ]);

        $detail = $trx->details()->create([
            'id_product' => $this->product->id_product,
            'quantity' => 1,
            'harga_beli' => 15000,
        ]);

        $this->actingAs($this->member);

        $response = $this->post(route('review.store'), [
            'id_transaction_detail' => $detail->id_transaction_detail,
            'rating' => 5,
            'komentar' => 'Sangat bagus!',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('review', [
            'id_transaction_detail' => $detail->id_transaction_detail,
            'id_user' => $this->member->id_user,
            'id_product' => $this->product->id_product,
            'rating' => 5,
            'komentar' => 'Sangat bagus!',
        ]);
    }

    public function test_user_can_update_profile(): void
    {
        $this->actingAs($this->member);

        $response = $this->post(route('profile.update'), [
            'nama' => 'Budi Santoso Baru',
            'no_hp' => '08999999999',
            'kode_pos' => '54321',
            'alamat' => 'Alamat Baru Budi',
        ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('user', [
            'id_user' => $this->member->id_user,
            'nama' => 'Budi Santoso Baru',
            'no_hp' => '08999999999',
            'kode_pos' => '54321',
            'alamat' => 'Alamat Baru Budi',
        ]);
    }

    public function test_cannot_add_product_of_inactive_kopdes_to_cart(): void
    {
        $this->kopdes->update(['status' => 'nonaktif']);

        $response = $this->post(route('cart.add'), [
            'id_product' => $this->product->id_product,
            'quantity' => 1,
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['success' => false]);
    }

    public function test_cannot_checkout_items_of_inactive_kopdes(): void
    {
        // Add to cart first while active
        $this->actingAs($this->member);
        $this->post(route('cart.add'), [
            'id_product' => $this->product->id_product,
            'quantity' => 1,
        ]);

        // Disable KopDes
        $this->kopdes->update(['status' => 'nonaktif']);

        // Attempt checkout
        $response = $this->post(route('checkout'), [
            'alamat_pengiriman' => 'Jalan Kebenaran No. 1',
            'catatan' => 'Segera kirim ya',
        ]);

        $response->assertSessionHasErrors(['error']);
    }
}
