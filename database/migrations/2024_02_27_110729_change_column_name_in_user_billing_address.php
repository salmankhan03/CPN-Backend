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
            $table->renameColumn('zip', 'zipcode');
            $table->renameColumn('phone', 'contact_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_billing_addresses', function (Blueprint $table) {
            $table->renameColumn('zipcode', 'zip');
            $table->renameColumn('contact_no', 'phone');
        });
    }
};
