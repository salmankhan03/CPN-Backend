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
        Schema::table('products', function (Blueprint $table) {

            $table->dateTime('sell_price_updated_at')->nullable();
            $table->dateTime('ratings_updated_at')->nullable();
            $table->dateTime('is_featured_updated_at')->nullable();

            $table->boolean('is_feaured')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sell_price_updated_at');
            $table->dropColumn('is_featured_updated_at');
            $table->dropColumn('ratings_updated_at');
            $table->dropColumn('is_feaured');
        });
    }
};
