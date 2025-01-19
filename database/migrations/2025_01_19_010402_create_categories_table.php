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
        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->json('name');
            $table->boolean('status')->default(1);
            $table->foreignId('business_type_id')->nullable()
            ->constrained('business_types','id')
            ->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('categories','category_id')->onDelete('cascade');
            $table->integer('level')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
