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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('address_id');
            $table->foreignId('user_id')
            ->constrained('users','user_id')
            ->onDelete('cascade');
            $table->string('address_long');
            $table->string('address_lat');
            $table->string('street')->nullable();
            $table->string('building')->nullable();
            $table->string('department')->nullable();
            $table->enum('type',['home','essential','work','other']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
