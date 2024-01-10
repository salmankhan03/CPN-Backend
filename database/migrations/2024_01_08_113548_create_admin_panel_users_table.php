<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::create('admin_panel_users', function (Blueprint $table) {
        if (!Schema::hasTable('users')) {
            // The "users" table exists...
            DB::statement('CREATE TABLE admin_panel_users LIKE users ');
        }
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_panel_users');
    }
};
