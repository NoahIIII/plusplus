<?php
namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => ['en' => fake()->company(), 'ar' => fake()->company()],
            'image' => 'https://dummyimage.com/640x480/cccccc/ffffff&text=Category',
            'status' => 1,
            'business_type_id' => 1,
            'parent_id' => null, // You can assign a parent category later
            'level' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Define a subcategory with a parent.
     */
    public function withParent(Category $parent): static
    {
        return $this->state(fn () => [
            'parent_id' => $parent->category_id,
            'level' => $parent->level + 1,
            'status' => 1,
            'business_type_id' => 1,
        ]);
    }
}
