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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('product_id');
            $table->string('quantity');
            $table->string('quantity_unit');
            $table->string('package_quantity');
            $table->string('quantity_available');
            $table->string('expiry_date');
            $table->string('date_first_available');
            $table->string('shipping_weight');
            $table->string('product_code');
            $table->string('dimensions');
            $table->string('upc_code');

            $table->string('description');
            $table->string('suggested_use');
            $table->string('other_ingredients');
            $table->string('warnings');
            $table->string('disclaimer');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
