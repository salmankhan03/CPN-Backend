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
        Schema::table('product_category', function (Blueprint $table) {

            $table->string('description');
            $table->string('parent_id');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_category', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('parent_id');
            $table->dropColumn('status');
        });
    }
};
