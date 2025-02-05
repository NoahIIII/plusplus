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
                    'discount_id' => $discount->discount_id,
                    'product_id' => $productId
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    /**
     * update discount
     */
    public function updateDiscount(Discount $discount, $data)
    {
        $data['status'] = $data['status'] ?? 0;
        try {
            DB::beginTransaction();
            // store the discount data
            $discount->update($data);
             // Get existing product discount IDs
    $existingProductIds = $discount->productDiscounts()->pluck('product_id')->toArray();
    $newProductIds = $data['product_ids'];

    // Identify products to be added and removed
    $productsToAdd = array_diff($newProductIds, $existingProductIds);
    $productsToRemove = array_diff($existingProductIds, $newProductIds);

    // Remove only the necessary product discounts
    if (!empty($productsToRemove)) {
        $discount->productDiscounts()->whereIn('product_id', $productsToRemove)->delete();
    }

    // Add new product discounts
    if (!empty($productsToAdd)) {
        $discount->productDiscounts()->createMany(
            collect($productsToAdd)->map(function ($productId) use ($discount) {
                return [
                    'discount_id' => $discount->discount_id,
                    'product_id' => $productId
                ];
            })->toArray()
        );
    }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
