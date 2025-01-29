<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $businessTypes = [
            [
                'name' => [
                    'en' => 'Pharmacy',
                    'ar' => 'صيدلية',
                ],
                'slug' => 'pharmacy',
                'icon'=>'ri-hospital-line',
                'model' => 'App\Models\PharmacyProduct',
            ],
            // [
            //     'name' => [
            //         'en' => 'Supermarket',
            //         'ar' => 'سوبر ماركت',
            //     ],
            //     'slug' => 'supermarket',
            // ],
            // [
            //     'name' => [
            //         'en' => 'Laundry',
            //         'ar' => 'مغسلة',
            //     ],
            //     'slug' => 'laundry',
            // ],
        ];

        foreach ($businessTypes as $businessType) {
            \App\Models\BusinessType::create($businessType);
        }
    }
}
