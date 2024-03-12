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

            $table->text('previous_order_status')->change();
            $table->text('current_order_status')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sent_order_status_update_email_log', function (Blueprint $table) {
            $table->bigInteger('previous_order_status')->change();
            $table->bigInteger('current_order_status')->change();
        });
    }
};
