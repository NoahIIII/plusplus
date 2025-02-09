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
            $table->foreignId('brand_id')
            ->nullable()
            ->constrained('brands','brand_id')
            ->onDelete('set null');
            $table->unsignedFloat('price')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->boolean('status');
            $table->string('primary_image')->nullable();
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
