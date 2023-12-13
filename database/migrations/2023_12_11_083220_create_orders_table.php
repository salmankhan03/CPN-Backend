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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('payment_id')->nullable(); // in case for COD orders
            $table->string('total_amount');
            $table->string('pecent_discount_applied')->nullable();
            $table->string('status')->nullable();
            $table->string('promo_code')->nullable();
            $table->boolean('is_guest')->nullable();
            $table->text('guest_user_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
