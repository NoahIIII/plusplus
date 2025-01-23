<?php

use App\Enums\PackageType;
use App\Enums\UnitType;
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
        Schema::create('package_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('pharmacy_products', 'product_id')
                ->onDelete('cascade');
            $table->enum('package_type', PackageType::values());
            $table->enum('unit_type', UnitType::values());
            $table->unsignedInteger('package_quantity')->nullable();
            $table->unsignedInteger('stock_quantity')->nullable();
            $table->unsignedFloat('price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_types');
    }
};
