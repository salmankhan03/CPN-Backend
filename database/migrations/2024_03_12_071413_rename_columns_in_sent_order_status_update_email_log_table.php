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
            $table->renameColumn('previous_order_status_id', 'previous_order_status');
            $table->renameColumn('current_order_status_id', 'current_order_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sent_order_status_update_email_log', function (Blueprint $table) {
            $table->renameColumn('previous_order_status', 'previous_order_status_id');
            $table->renameColumn('current_order_status', 'current_order_status_id');
        });
    }
};
