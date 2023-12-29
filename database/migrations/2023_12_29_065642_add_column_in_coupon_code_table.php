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
        Schema::table('coupon_code', function (Blueprint $table) {
            $table->boolean('is_eligible_for_free_shipping')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_code', function (Blueprint $table) {
            $table->dropColumn('is_eligible_for_free_shipping');
        });
    }
};
