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
        Schema::create('coupon_code', function (Blueprint $table) {
            $table->id();

            $table->string('code');
            $table->date('expires_at');
            $table->string('amount');
            $table->string('calculation_type');
            $table->string('minimum_amount');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_code');
    }
};
