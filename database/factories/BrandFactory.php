<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ['en' => fake()->company(), 'ar' => fake()->company()],
            'image' => 'https://dummyimage.com/640x480/cccccc/ffffff&text=Brand',
            'business_type_id' => 1,
            'status'=>1
        ];
    }
}
