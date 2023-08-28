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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('price');
            $table->string('currency');
            $table->string('produced_by');
            $table->string('shipping_weight');
            $table->string('product_code');
            $table->string('upc_code');
            $table->string('package_quantity');
            $table->string('dimensions');

            $table->string('description');
            $table->string('suggested_use');
            $table->string('other_ingredients');
            $table->string('warnings');
            $table->string('disclaimer');

            $table->boolean('is_visible');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
