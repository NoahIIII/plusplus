<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OffersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // the app should have 3 static offers at the moment (i guess)
        $businessTypes = \App\Models\BusinessType::all();
        $offers =[];
        foreach ($businessTypes as $businessType) {
            $offers = array_merge($offers, [
                [
                    'business_type_id' => $businessType->id,
                    'type' => 'buy_one_get_one',
                ],
                [
                    'business_type_id' => $businessType->id,
                    'type' => 'buy_one_get_two',
                ],
                [
                    'business_type_id' => $businessType->id,
                    'type' => 'buy_one_get_discount',
                ],
            ]);
        }
        \App\Models\Offer::insert($offers);
    }
}
