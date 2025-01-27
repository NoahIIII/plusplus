<?php

namespace App\Services;

use App\Models\PharmacyProduct;
use App\Models\ProductMedia;
use App\Pipelines\Filters\DataTableProductFilter;
use App\Pipelines\Filters\SortingPipeline;
use App\Traits\ApiResponseTrait;
use Illuminate\Pipeline\Pipeline;
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
            // store product media
            $product->media()->createMany($productData['media']);
            // store product packages (variants)
            if (isset($productData['variants'])) $product->packageTypes()->createMany($productData['variants']);

            DB::commit();
        } catch (\Exception $e) {
            // Rollback on error
            DB::rollBack();
            throw $e; // Re-throw the exception
        }
    }

    /**
     * update pharmacy product
     *
     * @param $product
     * @param array $data
     */
    public function updatePharmacyProduct(PharmacyProduct $product, array $data)
    {
        // format product data
        $productData = $this->formatProductData($data);

        try {
            // Start transaction
            DB::beginTransaction();
            $product->update($productData['product']);
            // sync product categories
            $product->categories()->sync($productData['categories']);
            // save product media & get the paths
            if (isset($data['images'])) {
                $productData['media'] = $this->processProductMedia($data['images']);
                $product->media()->createMany($productData['media']);
            }
            // delete product media
            if (isset($data['deleted_media_ids'])) {
                $media = $product->media()->whereIn('id', $data['deleted_media_ids'])->get();
                $this->deleteProductMedia($media);
            }
            // store product packages (variants)
            if (isset($productData['variants'])) {
                $this->updateProductVariants($productData['variants'], $product);
            } else {
                $product->packageTypes()->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return ApiResponseTrait::apiResponse([], __('admin.lot_is_required'), [1 => __('admin.lot_is_required')], 422);
    }
    /**
     * Destroy Pharmacy Products (with it's media&variants)
     * @param string $id
     */
    public function destroyPharmacyProduct(string $id)
    {
        // get the product
        $product = PharmacyProduct::findOrFail($id);
        // delete product media
        if ($product->media()->exists()) $this->deleteProductMedia($product->media);
        // delete product variants
        if ($product->packageTypes()->exists()) $product->packageTypes()->delete();
        // delete product
        $product->delete();
    }
    /**
     * filter products
     *
     * @param array $data
     * @return void
     */
    public function filterProducts($request)
    {
        // get request data
        $draw = (int) $request->input('draw');
        $start = (int)$request->input('start', 0);
        $length = (int)$request->input('length', 10);
        $totalRecords = PharmacyProduct::count();
        $query = PharmacyProduct::query();

        // filter the products through the pipeline
        $query = $this->applyFilters($query, $request);

        // Apply pagination
        $filteredCount = $query->count();
        if ($length === -1) {
            $length = $filteredCount;
        }
        $products = $query->skip($start)->take($length)->get();

        // Build response data
        $data = $this->formatResponseProductData($products);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredCount,
            'data' => $data
        ]);
    }
    /**
     * get product data from the request
     * @param array $data
     */
    private function formatProductData(array $data)
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
    private function processProductMedia(array $media)
    {
        // store the new media
        $paths = [];
        foreach ($media as $medium) {
            $paths[] = ['media' => StorageService::storeImage($medium, 'pharmacy-products', 'product-')];
        }
        return $paths;
    }

    /**
     * apply filters through the pipeline
     */

    private function applyFilters($query, $request)
    {
        return app(Pipeline::class)
            ->send($query)
            ->through([
                DataTableProductFilter::class,
                SortingPipeline::class
            ])
            ->thenReturn();
    }
    /**
     * delete all product media
     * @param $media
     */
    private function deleteProductMedia($media)
    {
        // delete the images
        foreach ($media as $medium) {
            StorageService::deleteFile($medium->media);
            $medium->delete();
        }
    }
    /**
     * paginate product data
     * @param $query
     * @param $start
     * @param $length
     */
    private function applyPagination($query, $start, $length)
    {
        if ($length === -1) {
            $length = $query->count();
        }

        return $query->skip($start)->take($length);
    }
    /**
     * Format product data for the response (database output)
     * @param $products
     */
    private function formatResponseProductData($products)
    {
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                'name_ar' => $product->getTranslation('name', 'ar'),
                'name_en' => $product->getTranslation('name', 'en'),
                'price' => $product->price,
                'quantity' => $product->quantity,
                'status' => $product->status
                    ? '<span class="badge dark-icon-light iq-bg-primary">' . ___('Active') . '</span>'
                    : '<span class="badge iq-bg-danger">' . ___('Inactive') . '</span>',
                'created_at' => $product->created_at->toDateTimeString(),
                'actions' => view('components.delete-button', [
                    'route' => route('pharmacy.products.destroy', $product->product_id),
                    'title' => __('Delete')
                ])->render() .
                    '<a class="iq-bg-primary ml-2" data-placement="top" title="' . __('Edit') . '"
                    data-original-title="' . __('Edit') . '"
                    href="' . route('pharmacy.products.edit', $product->product_id) . '">
                    <i class="ri-pencil-line"></i>
                </a>',
            ];
        }
        return $data;
    }

    /**
     * handle product variants update
     * @param $variants
     * @param $product
     */
    private function updateProductVariants($variants, $product)
    {
        // get product variant to delete
        $productVariantsIds = $product->packageTypes()->pluck('id')->toArray();
        $variantsToDelete = array_diff($productVariantsIds, array_column($variants, 'id'));
        // delete product variants
        if ($variantsToDelete) $product->packageTypes()->whereIn('id', $variantsToDelete)->delete();

        // update product variants
        foreach ($variants as $variant) {
            if (isset($variant['id'])) {
                $product->packageTypes()->where('id', $variant['id'])->update([
                    'package_type' => $variant['package_type'],
                    'unit_type' => $variant['unit_type'],
                    'unit_quantity' => $variant['unit_quantity'],
                    'stock_quantity' => $variant['stock_quantity'],
                    'price' => $variant['price'],
                ]);
            } else {
                $product->packageTypes()->create($variant);
            }
        }
    }
}
