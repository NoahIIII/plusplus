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
    /**
     * get product discounted price
     * @param $productId
     */
    public function getProductDiscount($product, $businessTypeID)
{
    // Default values
    $discountValue = 0;
    $discountedPrice = $product->price;

    // Check if there's a product discount
    $productDiscount = ProductDiscount::where('product_id', $product->product_id)->first();

    if ($productDiscount) {
        // Get the discount
        $discount = Discount::valid()
            ->where('apply_on', 'product')
            ->where('discount_id', $productDiscount->discount_id)
            ->where('business_type_id', $businessTypeID)
            ->where('status', 1)
            ->first();

        if ($discount) {
            if ($discount->type === 'percentage') {
                $discountValue = $discount->value;
            } else {
                // Convert fixed discount to percentage
                $discountValue = round(($discount->value / $product->price) * 100, 2);
            }
            $discountedPrice = round($product->price - ($product->price * $discountValue / 100), 2);
        }
    }

    return [
        'discount_value' => $discountValue, // Always percentage
        'discounted_price' => $discountedPrice
    ];
}

}
