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
        Schema::create('kopdes', function (Blueprint $table) {
            $table->id('id_kopdes');
            $table->string('nama_kopdes');
            $table->string('email')->unique();
            $table->string('no_telp');
            $table->text('alamat');
            $table->string('status')->default('aktif'); // aktif / nonaktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kopdes');
    }
};
