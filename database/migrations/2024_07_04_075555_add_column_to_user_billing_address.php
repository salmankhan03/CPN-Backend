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
        Schema::table('user_billing_addresses', function (Blueprint $table) {
            $table->boolean('is_default')->default(0);
            $table->boolean('is_added_by_user')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_billing_addresses', function (Blueprint $table) {
            $table->dropColumn('is_default');
            $table->dropColumn('is_added_by_user');

        });
    }
};
