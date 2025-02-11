<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            BusinessTypesSeeder::class,
            OffersSeeder::class,
        ]);

        // Run factories separately for local environment
        if (env('APP_ENV') === 'local') {
            User::factory()->count(100)->create();
            Brand::factory()->count(100)->create();

            // Generate hierarchical categories
            $mainCategories = Category::factory()->count(10)->create();

            $mainCategories->each(function ($mainCategory) {
                $subCategories = Category::factory()->count(3)->withParent($mainCategory)->create();

                $subCategories->each(function ($subCategory) {
                    Category::factory()->count(2)->withParent($subCategory)->create();
                });
            });
        }
    }
}
