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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name')->nullable();
            // $table->string('email')->unique();
            $table->string('phone')->unique(); // +201016023105
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('user_img')->nullable();
            $table->string('locale')->nullable()->default('ar');
            $table->boolean('status')->default(1);
            $table->boolean('online_status')->default(0);
            $table->unsignedInteger('points')->default(0);
            $table->foreignId('business_type_id')
            ->nullable()
            ->constrained('business_types','id');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
