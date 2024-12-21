<?php

namespace App\Services;

use App\Models\PostCatalogue;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\BaseService;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;


/**
 * Class UserService
 * @package App\Services
 */
class PostCatalogueService  extends BaseService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    protected $nestedSet;
    public function __construct(PostCatalogueRepository $postCatalogueRepository, Nestedsetbie $nestedSet)
    {
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->nestedSet = new Nestedsetbie(
            [
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
                'language_id' => $this->currentLanguage(),
            ]
        );
    }
    public function select()
    {
        return ['post_catalogues.id', 'post_catalogues.publish', 'post_catalogues.image', 'post_catalogues.level', 'post_catalogues.order', 'tb2.name', 'tb2.canonical'];
    }
    public function paginate($request)
    {


        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        $perpage = $request->integer('perpage');
        $postCatalogues = $this->postCatalogueRepository->pagination(
            $select,
            $condition,
            $perpage,
            ['path' => 'post.catalogue/index'],
            [],
            [
                'post_catalogues.lft' => 'asc',
            ],
            ['post_catalogue_language as tb2', 'tb2.post_catalogue_id', '=', 'post_catalogues.id'],

        );
        return $postCatalogues;
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {

            $payload = $request->only($this->payload());
            $payload['user_id'] = Auth::id();
            $postCatalogue = $this->postCatalogueRepository->create($payload);
            if ($postCatalogue->id > 0) {
                $payloadLanguage = $request->only($this->payloadLanguage());
                $payloadLanguage['language_id'] = $this->currentLanguage();
                $payloadLanguage['post_catalogue_id'] = $postCatalogue->id;
                $payloadLanguage['canonical'] = Str::slug($payloadLanguage['canonical']);
                $translate = $this->postCatalogueRepository->createTranslatePivot($postCatalogue, $payloadLanguage);
            }
            $this->nestedSet->Get();
            $this->nestedSet->Recursive(0, $this->nestedSet->Set());
            $this->nestedSet->Action();
            DB::commit();

            return $postCatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during postCatalogue creation: ' . $e->getMessage());
            throw new \Exception('Error creating the postCatalogue: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            dd($postCatalogue);
            $payload = $request->only($this->payload());
            $payload['user_id'] = Auth::id();
            dd($payload);
            $flag = $this->postCatalogueRepository->update($id, $payload);
            if ($flag) {

                $payloadLanguage = $request->only($this->payloadLanguage());
                $payloadLanguage['language_id'] = $this->currentLanguage();
                $payloadLanguage['post_catalogue_id'] = $id;
                $postCatalogue->languages()->detach([$payloadLanguage['language_id'], $id]);
                $response = $this->postCatalogueRepository->createTranslatePivot($postCatalogue, $payloadLanguage);
            }
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
            $user = $this->postCatalogueRepository->delete($id);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatus($post = [])
    {
        DB::beginTransaction();
        try {
            // dd($post);
            $payload[$post['field']] = (($post['value'] == '1') ? '0' : '1');
            $this->postCatalogueRepository->update($post['modelId'], $payload);
            // dd($post);
            $this->changeUserStatus($post, $payload[$post['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();

            return false;
        }
    }
    public function updateStatusAll($post)
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $flag = $this->postCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            $this->changeUserStatus($post, $payload[$post['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function changeUserStatus($post,  $value)
    {
        // dd($post);
        DB::beginTransaction();
        try {
            $array = [];
            if (isset($post['modelId'])) {
                $array[] = $post['modelId'];
            } else {
                $array = $post['id'];
            }
            $payload[$post['field']] = $value;
            $this->postCatalogueRepository->updateByWhereIn('post_catalogues.id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }
    private function payload()
    {
        return ['parent_id', 'follow', 'publish', 'image'];
    }
    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
}
