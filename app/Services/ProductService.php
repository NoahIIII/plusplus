<?php

namespace App\Services;

use App\Models\PharmacyProduct;
use Illuminate\Support\Facades\DB;

class ProductService
{

    /**
     * store pharmacy products
     * @param array $data
     * @return void
     */
    public function storePharmacyProduct(array $data)
    {
        // format product data
        $productData = $this->formatProductData($data);

        try {
            // Start transaction
            DB::beginTransaction();
            $product = PharmacyProduct::create($productData['product']);
            // sync product categories
            $product->categories()->sync($productData['categories']);
            // save product media & get the paths
            $productData['media'] = $this->processProductMedia($data['images']);
            // dd($productData['media']);
            // store product media
            $product->media()->createMany($productData['media']);
            // store product packages (variants)
            $product->packageTypes()->createMany($data['variants']);

            DB::commit();
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();
            throw $e; // Re-throw the exception
        }
    }

    /**
     * get product data from the request
     * @param array $data
     */
    public function formatProductData(array $data)
    {
        $data['status'] = $data['status'] ?? 0;
        $data['name'] = [
            'en' => $data['name_en'] ?? '',
            'ar' => $data['name_ar'] ?? '',
        ];
        $data['description'] = [
            'en' => $data['description_en'] ?? '',
            'ar' => $data['description_ar'] ?? '',
        ];
        $data['product'] = $data;
        unset($data['product']['images']);
        unset($data['product']['variants']);
        unset($data['product']['categories']);
        $data['categories'];
        return $data;
    }

    /**
     * store product media & return it's paths
     * @param array $media
     * @return array $paths
     */
    public function processProductMedia(array $media)
    {
        $paths = [];
        foreach ($media as $medium) {
            $paths[] = ['media' => StorageService::storeImage($medium, 'pharmacy-products', 'product-')];
        }
        return $paths;
    }
    /**
     * store product packages (variants)
     * @param array $data
     */
    public function storeProductPackages(array $data) {}
}
