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
            $table->string('sku')->nullable();
            $table->string('bar_code')->nullable();
            $table->string('quantity')->nullable();
            $table->string('slug')->nullable();
            $table->string('tags')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_category', function (Blueprint $table) {
            $table->dropColumn('sku');
            $table->dropColumn('bar_code');
            $table->dropColumn('quantity');
            $table->dropColumn('slug');
            $table->dropColumn('tags');
        });
    }
};
