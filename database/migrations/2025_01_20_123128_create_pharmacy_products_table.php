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
        Schema::create('pharmacy_products', function (Blueprint $table) {
            $table->id('product_id');
            $table->json('name');
            $table->json('description');
            $table->foreign('brand_id')
            ->nullable()
            ->references('brand_id')
            ->on('brands')
            ->onDelete('set null');
            $table->unsignedFloat('price')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->engine('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_products');
    }
};
