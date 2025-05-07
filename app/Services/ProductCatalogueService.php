<?php

namespace App\Services;

use App\Models\ProductCatalogue;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\ProductCatalogueServiceInterface;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Repositories\Interfaces\LanguageRepositoryInterface as languageRepository;
use App\Repositories\RouterRepository as RouterRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use App\Services\BaseService;
use Illuminate\Support\Str;
use App\Classes\Nestedsetbie;





/**
 * Class UserService
 * @package App\Services
 */
class ProductCatalogueService  extends BaseService implements ProductCatalogueServiceInterface
{
    protected $productCatalogueRepository;
    protected $languageRepository;
    // protected $nestedSet;
    protected $routerRepository;
    protected $controllerName = 'ProductCatalogueController';
    public function __construct(ProductCatalogueRepository $productCatalogueRepository, RouterRepository $routerRepository, languageRepository $languageRepository)
    {
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->languageRepository = $languageRepository;
        $this->routerRepository = $routerRepository;
        $this->nestedSet = new Nestedsetbie(
            [
                'table' => 'product_catalogues',
                'foreignkey' => 'product_catalogue_id',
                'language_id' => $this->currentLanguage(),
            ]
        );
    }
    public function select()
    {
        return ['product_catalogues.id', 'product_catalogues.publish', 'product_catalogues.image', 'product_catalogues.level', 'product_catalogues.order', 'tb2.name', 'tb2.canonical'];
    }
    public function paginate($request)
    {

        $locale = App::getLocale();
        $language = $this->languageRepository->findByCondition([['canonical', '=', $locale]]);
        $languageId = $language->id;
        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        $perpage = $request->integer('perpage');
        $productCatalogues = $this->productCatalogueRepository->pagination(
            $select,
            $condition,
            $perpage,
            ['path' => 'product/catalogue/index'],
            ['product_catalogues.lft' => 'asc'],
            [
                ['product_catalogue_language as tb2', 'tb2.product_catalogue_id', '=', 'product_catalogues.id']
            ],
            [],
            [
                ['tb2.language_id', '=', $languageId]
            ]
        );
        return $productCatalogues;
    }

    public function create($request)
    {
        // die();
        DB::beginTransaction();
        try {

            $productCatalogue = $this->createProductCatalogue($request);

            if ($productCatalogue->id > 0) {

                $payloadLanguage = $this->updateLanguageForCatalogue($productCatalogue, $request);
                // die();

                // dd($payloadLanguage);
                $this->createRouter($productCatalogue, $request, $this->controllerName, $this->currentLanguage());
                // die();
                // $this->routerRepository->create($routerPayload);
                $this->nestedSet();
            }
            DB::commit();

            return $productCatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during productCatalogue creation: ' . $e->getMessage());
            throw new \Exception('Error creating the productCatalogue: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        // echo 1;
        // die();
        DB::beginTransaction();
        try {


            $productCatalogue = $this->productCatalogueRepository->findById($id);
            $flag = $this->updateProductCatalogue($id, $request);
            if ($flag) {
                $this->updateLanguageForCatalogue($productCatalogue, $request);
            }

            $this->updateRouter($productCatalogue, $request, $this->controllerName, $this->currentLanguage());



            $this->nestedSet->Get();
            $this->nestedSet->Recursive(0, $this->nestedSet->Set());
            $this->nestedSet->Action();
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
            $products = $this->productCatalogueRepository->delete($id);
            $this->routerRepository->forceDeleteByWhere([
                ['module_id', '=', $id],
                ['controllers', '=', 'App\Http\Controller\Frontend\\' . $this->controllerName],
            ]);
            DB::commit();
            return $products;
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
            $this->productCatalogueRepository->update($product['modelId'], $payload);
            // dd($product);
            $this->changeUserStatus($product, $payload[$product['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($product)
    {
        DB::beginTransaction();
        try {
            $payload[$product['field']] = $product['value'];
            $flag = $this->productCatalogueRepository->updateByWhereIn('id', $product['id'], $payload);
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
            $this->productCatalogueRepository->updateByWhereIn('product_catalogues.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }

    private function updateProductCatalogue($id, $request)
    {
        // $productCatalogue = $this->productCatalogueRepository->findById($id);
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        $payload['album'] = json_encode($payload['album']);
        $flag = $this->productCatalogueRepository->update($id, $payload);
        return $flag;
    }

    private function createProductCatalogue($request)
    {
        $payload = $request->only($this->payload());
        // dd($payload);
        $payload['user_id'] = Auth::id();
        $payload['album'] = $this->formatAlbum($payload['album'] ?? null);
        // dd($payload['album']);
        // dd($payload['album']);
        // dd($payload);
        $productCatalogue = $this->productCatalogueRepository->create($payload);
        return $productCatalogue;
    }

    private function updateLanguageForCatalogue($productCatalogue, $request)
    {

        $payloadLanguage = $this->formatLanguagePayload($productCatalogue, $request);
        // dd($payloadLanguage);
        // dd($payloadLanguage['language_id']);
        $productCatalogue->languages()->detach([$payloadLanguage['language_id'], $productCatalogue->id]);
        $translate = $this->productCatalogueRepository->createPivot($productCatalogue, $payloadLanguage, 'languages');
        // dd($translate);
        return $translate;
    }

    private function formatLanguagePayload($productCatalogue, $request)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->currentLanguage();
        $payloadLanguage['product_catalogue_id'] = $productCatalogue->id;
        $payloadLanguage['canonical'] =  Str::slug($payloadLanguage['canonical']);
        // dd($payloadLanguage);
        return $payloadLanguage;
    }





    private function payload()
    {
        return ['parent_id', 'follow', 'publish', 'image', 'album'];
    }
    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
