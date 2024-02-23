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
        Schema::table('admin_panel_users', function (Blueprint $table) {
            $table->text('address')->nullable();
            $table->text('street')->nullable();
            $table->text('lendmark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_panel_users', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('street');
            $table->dropColumn('lendmark');
        });
    }
};
