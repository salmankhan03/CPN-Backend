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
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->string('name')->nullable();
            $table->string('price')->nullable();
            $table->string('bar_code')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->nullable();
            $table->string('quantity')->nullable();
            $table->string('sku')->nullable();
            $table->bigInteger('category_id');
            $table->boolean('is_combination')->default(0);
            $table->json('variants')->nullable();
            $table->json('tags')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
