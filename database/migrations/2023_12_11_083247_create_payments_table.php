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
        Schema::create('payments', function (Blueprint $table) {

            // this table will have only display and add functionality , no delete functionality
            // and status can be edited to PENDING >> RECEIVED , no RECEIVED >> PENDING

            $table->id();

            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->boolean('is_guest')->nullable();
            $table->text('external_payment_id')->nullable(); // in case of COD , it is NULL
            $table->string('payment_gateway_name')->nullable(); // in case of COD
            $table->string('type')->nullable(); // COD , UPI , NET BANKING , ACH ,WIRE ,NACH
            $table->boolean('is_order_cod')->nullable();
            $table->boolean('is_cod_paymend_received')->nullable();
            $table->string('amount');
            $table->string('status'); // PENDING, RECEIVED , REVERTED
            $table->json('payment_data')->nullable();

            // will add payment card data for future use

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
