<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add kota + kecamatan to kopdes
        Schema::table('kopdes', function (Blueprint $table) {
            $table->string('kota')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kota');
        });

        // 2. Enforce 1 review per user per product:
        //    Remove any duplicates first (keep newest reviewed_at), then add unique index.
        //    MySQL-safe: delete all but max id_review per (id_user, id_product) pair.
        DB::statement("
            DELETE r1 FROM review r1
            INNER JOIN review r2
            ON r1.id_user = r2.id_user
               AND r1.id_product = r2.id_product
               AND r1.id_review < r2.id_review
        ");

        Schema::table('review', function (Blueprint $table) {
            $table->unique(['id_user', 'id_product'], 'review_user_product_unique');
        });
    }

    public function down(): void
    {
        Schema::table('review', function (Blueprint $table) {
            $table->dropUnique('review_user_product_unique');
        });

        Schema::table('kopdes', function (Blueprint $table) {
            $table->dropColumn(['kota', 'kecamatan']);
        });
    }
};
