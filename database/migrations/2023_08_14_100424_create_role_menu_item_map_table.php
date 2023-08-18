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
        Schema::create('role_menu_item_map', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('role_id');
            $table->integer('menu_item_id');
            $table->bigInteger('updated_by_user_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_menu_item_map');
    }
};