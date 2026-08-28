<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kopdes_id');
            $table->unsignedBigInteger('user_id');
            $table->string('invoice_number')->unique();
            $table->integer('total_amount');
            $table->string('payment_proof')->nullable();
            $table->enum('status', ['Menunggu', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'])->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};