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
        Schema::create('sent_order_status_update_email_log', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('order_id');
            $table->bigInteger('previous_order_status_id');
            $table->bigInteger('current_order_status_id');
            $table->text('email_body');
            $table->text('from_email');
            $table->json('to_email');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_order_status_update_email_log');
    }
};
