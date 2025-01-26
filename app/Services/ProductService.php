<?php

namespace App\Services;

use App\Models\PharmacyProduct;
use App\Pipelines\Filters\DataTableProductFilter;
use App\Pipelines\Filters\SortingPipeline;
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
                ])->render(),
            ];
        }
        return $data;
    }
}
