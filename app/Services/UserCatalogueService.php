<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface as UserCatalogueRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;

/**
 * Class UserService
 * @package App\Services
 */
class UserCatalogueService  implements UserCatalogueServiceInterface
{
    protected $userCatalogueRepository;
    protected $userRepository;
    public function __construct(UserCatalogueRepository $userCatalogueRepository, UserRepository $userRepository)
    {
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->userRepository = $userRepository;
    }
    public function select()
    {
        return ['id', 'name', 'publish', 'description', 'deleted_at'];
    }
    public function paginate($request)
    {


        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        $perpage = $request->integer('perpage');

        $userCatalogues = $this->userCatalogueRepository->pagination($select, $condition, '', $perpage, ['path' => 'user/catalogue/index'], ['users']);
        // echo 1;
        // die();
        // dd($userCatalogues);
        return $userCatalogues;
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except(['_token', 'send']);
            $usercatalogue = $this->userCatalogueRepository->create($payload);

            DB::commit();
            return $usercatalogue;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            // echo 1;
            // die();
            // dd($id);
            $user = $this->userCatalogueRepository->findById($id);
            $payload = $request->except(['_token', 'send']);
            $user = $this->userCatalogueRepository->update($id, $payload);

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = $this->userCatalogueRepository->delete($id);

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
            $user = $this->userCatalogueRepository->update($post['modelId'], $payload);
            // dd($post);
            $this->changeUserStatus($post, $payload[$post['field']]);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
            return false;
        }
    }
    public function updateStatusAll($post)
    {
        DB::beginTransaction();
        try {
            $payload[$post['field']] = $post['value'];
            $flag = $this->userCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            $this->changeUserStatus($post, $payload[$post['field']]);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            die();
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
            die();
            return false; // Trả về false nếu có lỗi
        }
    }
}
