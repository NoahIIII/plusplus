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
        Schema::create('brand_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')
            ->constrained('discounts','discount_id')
            ->cascadeOnDelete();
            $table->foreignId('brand_id')
            ->constrained('brands','brand_id')
            ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_discounts');
    }
};
