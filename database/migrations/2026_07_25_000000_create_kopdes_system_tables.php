<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. kopdes
        Schema::create('kopdes', function (Blueprint $table) {
            $table->id('id_kopdes');
            $table->string('nama_kopdes');
            $table->text('alamat');
            $table->string('no_hp');
            $table->string('status')->default('aktif');
            $table->timestamps();
        });

        // 2. Add id_kopdes FK to user table
        Schema::table('user', function (Blueprint $table) {
            $table->foreignId('id_kopdes')->nullable()->after('id_role')->constrained('kopdes', 'id_kopdes')->onDelete('set null');
        });

        // 3. category
        Schema::create('category', function (Blueprint $table) {
            $table->id('id_category');
            $table->foreignId('id_kopdes')->constrained('kopdes', 'id_kopdes')->onDelete('cascade');
            $table->string('nama_kategori');
            $table->timestamps();
        });

        // 4. product
        Schema::create('product', function (Blueprint $table) {
            $table->id('id_product');
            $table->foreignId('id_kopdes')->constrained('kopdes', 'id_kopdes')->onDelete('cascade');
            $table->foreignId('id_category')->constrained('category', 'id_category')->onDelete('cascade');
            $table->string('nama_produk');
            $table->text('deskripsi');
            $table->decimal('harga', 12, 2);
            $table->integer('stok');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        // 5. cart
        Schema::create('cart', function (Blueprint $table) {
            $table->id('id_cart');
            $table->foreignId('id_user')->constrained('user', 'id_user')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('product', 'id_product')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });

        // 6. transaction
        Schema::create('transaction', function (Blueprint $table) {
            $table->id('id_transaction');
            $table->foreignId('id_user')->constrained('user', 'id_user')->onDelete('cascade');
            $table->foreignId('id_kopdes')->constrained('kopdes', 'id_kopdes')->onDelete('cascade');
            $table->string('kode_transaksi')->unique();
            $table->decimal('total_harga', 12, 2);
            $table->string('status_transaksi')->default('menunggu_pembayaran');
            $table->text('alamat_pengiriman');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 7. transaction_detail
        Schema::create('transaction_detail', function (Blueprint $table) {
            $table->id('id_transaction_detail');
            $table->foreignId('id_transaction')->constrained('transaction', 'id_transaction')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('product', 'id_product')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('harga_beli', 12, 2);
            $table->timestamps();
        });

        // 8. payment
        Schema::create('payment', function (Blueprint $table) {
            $table->id('id_payment');
            $table->foreignId('id_transaction')->constrained('transaction', 'id_transaction')->onDelete('cascade');
            $table->decimal('jumlah_bayar', 12, 2);
            $table->string('metode_pembayaran');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('status_pembayaran')->default('menunggu_verifikasi');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('user', 'id_user')->onDelete('set null');
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->timestamps();
        });

        // 9. review
        Schema::create('review', function (Blueprint $table) {
            $table->id('id_review');
            $table->foreignId('id_user')->constrained('user', 'id_user')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('product', 'id_product')->onDelete('cascade');
            $table->tinyInteger('rating');
            $table->text('komentar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review');
        Schema::dropIfExists('payment');
        Schema::dropIfExists('transaction_detail');
        Schema::dropIfExists('transaction');
        Schema::dropIfExists('cart');
        Schema::dropIfExists('product');
        Schema::dropIfExists('category');
        
        // Remove foreign key id_kopdes from user table
        Schema::table('user', function (Blueprint $table) {
            $table->dropForeign(['id_kopdes']);
            $table->dropColumn('id_kopdes');
        });

        Schema::dropIfExists('kopdes');
    }
};
