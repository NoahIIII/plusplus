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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id('discount_id');
            $table->foreignId('business_type_id')
            ->constrained('business_types','id')
            ->onDelete('cascade');
            $table->enum('type', ['percentage', 'fixed','buy_one_get_one']);
            $table->unsignedInteger('value');
            $table->string('code')->nullable();
            $table->boolean('status')->default(1);
            $table->enum('apply_on', ['product','order']);
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
