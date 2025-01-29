<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\ProductDiscount;
use Illuminate\Support\Facades\DB;

class DiscountService
{
    /**
     * store discount

     */
    public function storeDiscount($data)
    {
        $data['status'] = $data['status'] ?? 0;
        $data['apply_on'] = 'product';
        try {
            DB::beginTransaction();
            // store the discount data
            $discount = Discount::create($data);
            // store the product discounts data
            foreach ($data['product_ids'] as $productId) {
                ProductDiscount::create([
                    'discount_id' => $discount->id,
                    'product_id' => $productId
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
