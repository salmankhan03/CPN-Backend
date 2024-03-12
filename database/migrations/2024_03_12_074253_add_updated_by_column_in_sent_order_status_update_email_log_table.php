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
        Schema::table('sent_order_status_update_email_log', function (Blueprint $table) {
            $table->bigInteger('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sent_order_status_update_email_log', function (Blueprint $table) {
            $table->dropColumn('updated_by');
        });
    }
};
