<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\ProductServiceInterface;
use App\Repositories\ProductRepository as ProductRepository;
use App\Repositories\ProductVariantLanguageRepository as ProductVariantLanguageRepository;
use App\Repositories\ProductVariantAttributeRepository as ProductVariantAttributeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\BaseService;
use App\Repositories\RouterRepository as RouterRepository;

use Illuminate\Support\Str;


/**
 * Class UserService
 * @package App\Services
 */
class ProductService  extends BaseService implements ProductServiceInterface
{
    protected $productRepository;
    protected $nestedSet;
    protected $controllerName = 'ProductController';
    protected $productVariantLanguageRepository;
    protected $productVariantAttributeRepository;

    public function __construct(ProductRepository $productRepository, RouterRepository $routerRepository, ProductVariantLanguageRepository $productVariantLanguageRepository, ProductVariantAttributeRepository $productVariantAttributeRepository)
    {
        $this->productRepository = $productRepository;
        $this->routerRepository = $routerRepository;
        $this->productVariantLanguageRepository = $productVariantLanguageRepository;
        $this->productVariantAttributeRepository = $productVariantAttributeRepository;
    }
    public function select()
    {
        return ['products.id', 'products.publish', 'products.image', 'products.order', 'tb2.name', 'products.attributeCatalogue'];
    }
    public function paginate($request)
    {
        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        $condition['where'] = [
            ['tb2.language_id', '=', $this->currentLanguage()],
        ];

        $perPage = $request->integer('perpage');
        $joins = [
            ['product_language as tb2', 'tb2.product_id', '=', 'id'],
            ['product_catalogue_product as tb3', 'products.id', '=', 'tb3.product_id']
        ];





        return $this->productRepository->pagination(
            $select,         // Columns to select
            $condition,      // Conditions
            $perPage,        // Items per page
            ['path' => 'product/index', 'groupBy' => $this->select()], // Extended options
            ['products.id' => 'DESC'], // Order by
            $joins,
            ['product_catalogues'],
            $this->whereRaw($request),
        );
    }


    public function create($request)
    {
        DB::beginTransaction();
        try {

            $product = $this->createProduct($request);

            if ($product->id > 0) {
                $this->updateLanguageForProduct($product, $request);
                $this->updateCatalogueForProduct($product, $request);
                $this->createRouter($product, $request, $this->controllerName, $this->currentLanguage());
                $this->createVariant($product, $request);
            }
            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during product creation: ' . $e->getMessage());
            throw new \Exception('Error creating the product: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }

    private function createVariant($product, $request)
    {

        $payload = $request->only(['attributes', 'variants']);
        $variantPayload = $payload['variants'] ?? [];

        $variant = $this->createVariantArray($variantPayload);
        $product->product_variants()->delete();
        $variants = $product->product_variants()->createMany($variant);
        $variantIds = $variants->pluck('id');
        if (count($variantIds)) {
            foreach ($variantIds as $key => $val) {
                $code = $payload['variants'][$key]['attribute_ids'];
                $attributeIds = explode('-', $code);
                $productVariantLanguage[] = [
                    'product_variant_id' => $val,
                    'language_id' => $this->currentLanguage(),
                    'name' => $payload['variants'][$key]['name']
                ];

                foreach ($attributeIds as $attributeId) {
                    $productVariantAttribute[] = [
                        'product_variant_id' => $val,
                        'attribute_id' => (int)$attributeId,
                    ];
                }
            }

            // Batch insert product variant languages using the repository
            $res = $this->productVariantLanguageRepository->createBatch($productVariantLanguage);
            $this->productVariantAttributeRepository->createBatch($productVariantAttribute);
        }
    }

    private function createVariantArray($payload)
    {

        foreach ($payload as $variantData) {
            $data[] = [
                'name' => $variantData['name'] ?? null,
                'sku'            => $variantData['sku'] ?? null,
                'quantity'       => $variantData['quantity'] ?? 0,
                'price'          => str_replace('.', '', $variantData['price']) ?? 0, // Chuyển về int
                'code'  => $variantData['attribute_ids'] ?? '',
                'album'          => $variantData['album'] ?? null,
                'file_name'      => $variantData['file_name'] ?? null,
                'file_url'      => $variantData['file_path'] ?? null,
                'user_id' => Auth::id(),
            ];
        }
        return $data;
    }


    public function update($id, $request)
    {
        DB::beginTransaction();
        try {

            $product = $this->productRepository->findById($id);

            // $flag = $this->productRepository->update($id, $payload);
            if ($this->updateProduct($product->id, $request)) {


                $this->updateLanguageForProduct($product, $request);
                $this->updateCatalogueForProduct($product, $request);
                $this->updateRouter($product, $request, $this->controllerName, $this->currentLanguage());

                $product->product_variants()->each(function ($variant) {
                    $variant->languages()->detach();
                    $variant->attributes()->detach();
                    $variant->delete();
                });
                $this->createVariant($product, $request);
            }
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false;
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->productRepository->delete($id);
            $this->routerRepository->forceDeleteByWhere([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controller\Frontend\\' . $this->controllerName],
            ]);


            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatus($product = [])
    {
        DB::beginTransaction();
        try {
            // dd($product);
            $payload[$product['field']] = (($product['value'] == '1') ? '0' : '1');
            $this->productRepository->update($product['modelId'], $payload);
            $this->changeUserStatus($product, $payload[$product['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($product = [])
    {
        DB::beginTransaction();
        try {
            $payload[$product['field']] = $product['value'];
            $flag = $this->productRepository->updateByWhereIn('id', $product['id'], $payload);
            $this->changeUserStatus($product, $payload[$product['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function changeUserStatus($product,  $value)
    {
        // dd($product);
        DB::beginTransaction();
        try {
            $array = [];
            if (isset($product['modelId'])) {
                $array[] = $product['modelId'];
            } else {
                $array = $product['id'];
            }
            $payload[$product['field']] = $value;
            $this->productRepository->updateByWhereIn('products.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function createProduct($request)
    {

        $payload = $request->only($this->payload());
        $payload['attributeCatalogue'] = json_encode($payload['attribute_catalogue_id']);
        unset($payload['attribute_catalogue_id']);
        $payload['attributes'] = json_encode($this->getAttribute($request));
        $payload['variants'] = json_encode($payload['variants']);
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload);
        $product = $this->productRepository->create($payload);
        // dd($product);
        return $product;
    }



    private function getAttribute($request)
    {
        $attributes = $request->input('attributes', []);
        $result = [];

        foreach ($attributes as $index => $attribute) {
            $result[$index] = $attribute['values'];
        }
        return $result;
    }


    private function updateProduct($id, $request)
    {
        $payload = $request->only($this->payload());
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload['album']);
        // dd($id);
        return $this->productRepository->update($id, $payload);
    }


    private function updateLanguageForProduct($product, $request)
    {
        $payload = $request->only($this->payloadLanguage());
        $payload = $this->formatLanguagePayload($payload, $product->id);
        $product->languages()->detach([$this->currentLanguage(), $product->id]);
        return  $this->productRepository->createPivot($product, $payload, 'languages');
    }

    private function formatLanguagePayload($payload, $productId)
    {
        $payload['canonical'] =  Str::slug($payload['canonical']);
        $payload['language_id'] = $this->currentLanguage();
        $payload['product_id'] = $productId;
        return $payload;
    }
    private function updateCatalogueForProduct($product, $request)
    {
        $product->product_catalogues()->sync($this->catalogue($request));
    }

    private function catalogue($request)
    {
        // Lấy dữ liệu từ request, đảm bảo nó là một mảng
        $productCatalogueIds = $request->input('product_catalogue', []);

        // Ép kiểu thành mảng (nếu là null hoặc chuỗi)
        $productCatalogueIds = is_array($productCatalogueIds) ? $productCatalogueIds : [$productCatalogueIds];

        // Loại bỏ giá trị trống và trùng lặp
        return array_filter(array_unique($productCatalogueIds));
    }

    private function whereRaw($request)
    {
        $rawCondition = [];
        // dd($request->integer('product_catalogue_id'));
        if ($request->integer('product_catalogue_id') > 0) {
            $rawCondition['whereRaw'] =
                [
                    [
                        'tb3.product_catalogue_id IN (SELECT id FROM product_catalogues
                    WHERE lft >= (SELECT lft FROM product_catalogues AS pc WHERE pc.id = ?)
                    AND rgt <= (SELECT rgt FROM product_catalogues AS pc WHERE pc.id = ?)
                )',
                        [$request->integer('product_catalogue_id'), $request->integer('product_catalogue_id')]
                    ]
                ];
        }
        // dd($rawCondition);
        return $rawCondition;
        // dd($rawCondition);
    }

    private function payload()
    {
        return ['follow', 'publish', 'image', 'album', 'product_catalogue_id', 'attribute_catalogue_id', 'attribute', 'variants'];
    }
    private function payloadLanguage()
    {
        return ['name', 'product_id', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
