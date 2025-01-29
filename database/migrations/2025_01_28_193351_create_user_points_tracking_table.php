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
        Schema::create('user_points_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained('users','user_id')
            ->onDelete('cascade');
            $table->unsignedInteger('points');
            $table->enum('type', ['add', 'deduct']);
            $table->string('action');
            // $table->foreignId('order_id')->nullable()
            // ->constrained('orders','order_id')
            // ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_points_tracking');
    }
};
