<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\PermissionServiceInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface as PermissionRepository;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Class UserService
 * @package App\Services
 */
class PermissionService  implements PermissionServiceInterface
{
    protected $permissionRepository;
    protected $userRepository;
    public function __construct(PermissionRepository $permissionRepository, UserRepository $userRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->userRepository = $userRepository;
    }
    public function select()
    {
        return ['id', 'name', 'canonical'];
    }
    public function paginate($request)
    {


        $select = $this->select();
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->input('publish');
        $perpage = $request->integer('perpage');
        $permissions = $this->permissionRepository->pagination($select, $condition, $perpage, ['path' => 'permission/index'], [], [], []);
        return $permissions;
    }

    public function create($request)
    {
        DB::beginTransaction();
        try {
            // dd($request->all());
            $payload = $request->except(['_token', 'send']);
            $payload['user_id'] = Auth::id();
            $permission = $this->permissionRepository->create($payload);
            DB::commit();

            return $permission; // Return the created model
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during permission creation: ' . $e->getMessage());
            throw new \Exception('Error creating the permission: ' . $e->getMessage()); // Rethrow with a custom message
        }
    }


    public function update($id, $request)
    {
        DB::beginTransaction();
        try {

            $user = $this->permissionRepository->findById($id);
            $payload = $request->except(['_token', 'send']);
            $permission = $this->permissionRepository->update($id, $payload);

            DB::commit();
            return $permission;
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
            $user = $this->permissionRepository->delete($id);

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
            $this->permissionRepository->update($post['modelId'], $payload);
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
            $flag = $this->permissionRepository->updateByWhereIn('id', $post['id'], $payload);
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
            $permission = $this->permissionRepository->update($id, ['current' => 1]);
            $payload = ['current' => 0];
            $where = [
                ['id', '!=', $id]
            ];
            $this->permissionRepository->updateByWhere($where, $payload);
            DB::commit();
            return true; // Phải trả về true nếu cập nhật thành công
        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            // die();
            return false; // Trả về false nếu có lỗi
        }
    }
}
