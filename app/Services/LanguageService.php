<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\LanguageServiceInterface;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class UserService
 * @package App\Services
 */
class LanguageService  implements LanguageServiceInterface
{
    protected $languageRepository;
    protected $userRepository;
    protected $routerRepository;
    public function __construct(LanguageRepository $languageRepository, UserRepository $userRepository, RouterRepository $routerRepository)
    {
        $this->languageRepository = $languageRepository;
        $this->userRepository = $userRepository;
        $this->routerRepository = $routerRepository;
    }
    public function select()
    {
        return ['id', 'name', 'canonical', 'publish', 'image', 'current'];
    }
    public function paginate($request)
    {


        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        $perpage = $request->integer('perpage');
        $languages = $this->languageRepository->pagination($select, $condition, $perpage, ['path' => 'language/index'], [], [], []);
        // dd($languages);
        return $languages;
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $payload['user_id'] = Auth::id();
            $language = $this->languageRepository->create($payload);
            DB::commit();
            return $language;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during language creation: ' . $e->getMessage());
            throw new \Exception('Error creating the language: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        DB::beginTransaction();
        try {

            $user = $this->languageRepository->findById($id);
            $payload = $request->except(['_token', 'send']);
            $language = $this->languageRepository->update($id, $payload);

            DB::commit();
            return $language;
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
            $user = $this->languageRepository->delete($id);

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
            $this->languageRepository->update($post['modelId'], $payload);
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
            $flag = $this->languageRepository->updateByWhereIn('id', $post['id'], $payload);
            $this->changeUserStatus($post, $payload[$post['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
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
            $this->userRepository->updateByWhereIn('user_catalogue_id', $array, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }

    public function switch($id)
    {
        // dd($post);
        DB::beginTransaction();
        try {
            $language = $this->languageRepository->update($id, ['current' => 1]);
            $payload = ['current' => 0];
            $where = [
                ['id', '!=', $id]
            ];
            $this->languageRepository->updateByWhere($where, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }
    public function saveTranslate($option, $request)
    {
        // dd($option['model']);

        DB::beginTransaction();
        try {
            $payload = [
                'name' => $request->input('translate_name'),
                'description' => $request->input('translate_description'),
                'content' => $request->input('translate_content'),
                'meta_title' => $request->input('translate_meta_title'),
                'meta_keyword' => $request->input('translate_meta_keyword'),
                'meta_description' => $request->input('translate_meta_description'),
                'canonical' => $request->input('translate_canonical'),
                // 'post_catalogue_id' => $option['id'],
                'language_id' => $option['LanguageId']
            ];
            $repositoryNameSpace = 'App\Repositories\\' . ucfirst($option['model']) . 'Repository';
            // dd($repositoryNameSpace);
            if (class_exists($repositoryNameSpace)) {

                $repositoryInstance = app($repositoryNameSpace);
            }
            // dd($repositoryInstance);
            // dd($option['model']);
            $model = $repositoryInstance->findById($option['id']);
            // dd($model);
            $model->languages()->detach([$option['LanguageId'], $model->id]);


            $repositoryInstance->createTranslatePivot($model, $payload, 'languages', $option['LanguageId']);
            // dd($option);

            $this->routerRepository->forceDeleteByWhere([
                ['module_id', '=', $model->id],
                ['language_id', '=', $option['LanguageId']],
                ['controllers', '=', 'App\Http\Controller\Frontend\\' . $option['model'] . 'Controller'],

            ]);


            $routerPayload = [
                'canonical' => is_array($request) ? ($request['translate_canonical'] ?? null) : $request->input('translate_canonical'),
                'module_id' => $model->id,
                'language_id' => $option['LanguageId'],
                'controllers' => 'App\Http\Controller\Frontend\\' . $option['model'] . 'Controller',
            ];
            $this->routerRepository->create($routerPayload);


            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }
    public function modelIdConvert($model)
    {
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
        return $temp . '_id';
    }
}
