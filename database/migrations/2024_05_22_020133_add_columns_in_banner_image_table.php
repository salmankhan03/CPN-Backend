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
        Schema::table('banner_image', function (Blueprint $table) {

            $table->text('heading')->nullable();
            $table->text('content')->nullable();
            $table->text('button_label')->nullable();
            $table->text('button_url')->nullable();
            $table->text('content_position')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_image', function (Blueprint $table) {
            
            $table->dropColumn('heading');
            $table->dropColumn('content');
            $table->dropColumn('button_label');
            $table->dropColumn('button_url');
            $table->dropColumn('content_position');

        });
    }
};
