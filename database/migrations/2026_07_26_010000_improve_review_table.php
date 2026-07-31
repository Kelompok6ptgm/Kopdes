<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('review', function (Blueprint $table) {
            // Bind review to a specific order line item (not just user+product)
            $table->foreignId('id_transaction_detail')
                ->nullable()
                ->after('id_product')
                ->constrained('transaction_detail', 'id_transaction_detail')
                ->onDelete('cascade');

            // Manager can reply to a review (nullable = no reply yet)
            $table->text('tanggapan_manager')->nullable()->after('komentar');

            // Timestamp when review was first submitted (for time-limit enforcement later)
            $table->timestamp('reviewed_at')->nullable()->after('tanggapan_manager');
        });
    }

    public function down(): void
    {
        Schema::table('review', function (Blueprint $table) {
            $table->dropForeign(['id_transaction_detail']);
            $table->dropColumn(['id_transaction_detail', 'tanggapan_manager', 'reviewed_at']);
        });
    }
};
