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
        Schema::create('staff_users', function (Blueprint $table) {
            $table->id('staff_user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('staff_user_img')->nullable()->default('admins/default_user.png');
            $table->string('password');
            $table->boolean('status')->default(1);
            $table->string('locale')->nullable()->default('en');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_users');
    }
};
